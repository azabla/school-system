public function fetch_kg_result_reportWeek($branch, $gradesec, $chibt, $week, $max_year)
	{
	    // Get Grade
	    $gradeRow = $this->db->select('grade')
	        ->where(['branch' => $branch, 'gradesec' => $gradesec, 'academicyear' => $max_year])
	        ->group_by('grade')
	        ->get('users')
	        ->row();

	    if (!$gradeRow) {
	        return '<div class="alert alert-light">Ooops, no record found for this grade.</div>';
	    }
	    $grade = $gradeRow->grade;

	    // Check Subject Header
	    $subExists = $this->db->select('sub_name')
	        ->where(['sub_name' => $chibt, 'header_grade' => $grade, 'academicyear' => $max_year])
	        ->group_by('sub_name')
	        ->get('kg_subject_header')
	        ->num_rows();

	    if ($subExists <= 0) {
	        return '<div class="alert alert-light">Ooops, no record found for this subject.</div>';
	    }

	    // School Info
	    $school = $this->db->get('school')->row();

	    // Homeroom Teacher
	    $teacher = $this->db->select('u.fname, u.mname, u.mysign')
	        ->from('users u')
	        ->join('hoomroomplacement hm', 'hm.teacher=u.username AND hm.branch=u.branch')
	        ->where([
	            'hm.roomgrade' => $gradesec,
	            'hm.academicyear' => $max_year,
	            'hm.branch' => $branch,
	            'u.status' => 'Active',
	            'u.isapproved' => '1'
	        ])
	        ->get()
	        ->row_array();

	    $tfName = $teacher['fname'] ?? '___________';
	    $tmName = $teacher['mname'] ?? '___________';
	    $signSigns = $teacher['mysign'] ?? '_____';

	    // Students
	    $students = $this->db->where([
	            'academicyear' => $max_year,
	            'status' => 'Active',
	            'isapproved' => '1',
	            'usertype' => 'Student',
	            'branch' => $branch,
	            'gradesec' => $gradesec
	        ])
	        ->order_by('fname, mname, lname', 'ASC')
	        ->get('users')
	        ->result();

	    if (empty($students)) {
	        return '<div class="alert alert-warning">No students found.</div>';
	    }

	    // Subject Value Keys
	    $keys = $this->db->where([
	            'academicyear' => $max_year,
	            'value_grade' => $grade
	        ])
	        ->get('kg_subject_value')
	        ->result();
	    $totalKey = count($keys);

	    // Report container start
	    $output = '<div class="grandKGReport-container">';
	    foreach ($students as $student) {
	    // School Header
	    $output .= '<div style="width:100%;height:auto;page-break-inside:avoid;display: block; ">
	      <div class="support-ticket media pb-1 mb-3 card-header">
	          <img src="'.base_url().'/logo/'.$school->logo.'" style="width:auto;height: 110px;" class="user-img mr-2" alt="">
	          <div class="media-body ml-3">
	            <span class="font-weight-bold">
	              <h2><b>'.$school->name_2.' <br>'.$school->name.'</b></h2>
	            </span>
	            <p class="my-1">
	              <h4><b><u>የተማሪዎች የምልከታ መከታተያ ሪፖርት | Student Progress Report Card</u></b></h4>
	            </p>
	          </div>
	      </div>';

	    // For each student
	    
	        $username = $student->username;

	        $output .= '
	        <div class="grandKGReport-student card">
	            <div class="card-body">
	                <p>የህፃኑ/ኗ ስም:<strong> ' . $student->fname . ' ' . $student->mname . ' ' . $student->lname . '</strong> 
	                &nbsp; ፆታ: <strong>' . $student->gender . '</strong></p>
	                <p>ጭብጥ:<strong> ' . $chibt . '</strong> &nbsp; <strong>ደረጃ:</strong> ' . $gradesec . '</p>
	                <p>የመምህር ስም: <strong>' . $tfName . ' ' . $tmName . '</strong> &nbsp; 
	                የትምህርት ዘመን:<strong>' . $max_year . '</strong></p>

	                <div class="table-responsive">
	                    <table class="tabler table-borderedr grandKGReport-table">
	                        <thead>
	                            <tr>
	                                <th>Week</th>
	                                <th>No.</th>
	                                <th>Learning Objectives</th>';
	                                foreach ($keys as $key) {
								        $output .= '<th>' . $key->value_name . '</th>';
								    }
	                            $output.='</tr>
	                        </thead>
	                        <tbody>';

	        $noCategory = 1;
	        foreach ($week as $weekName) {
			    $weekLabel = $this->translate_week($weekName);

			    // Calculate total rows for this week
			    $totalRows = 1; // for header row (No. | Learning Objectives | values)

			    // Fetch categories
			    $categories = $this->db->select('kc.category_name')
			        ->from('kg_subject_list_name bs')
			        ->join('kg_subject_list_category kc', 'kc.category_name = bs.scategory')
			        ->where([
			            'kc.cate_term' => $chibt,
			            'kc.cate_grade' => $grade,
			            'kc.academicyear' => $max_year,
			            'bs.sgrade' => $grade,
			            'bs.sterm' => $chibt,
			            'bs.academicyear' => $max_year,
			            'bs.week' => $weekName
			        ])
			        ->group_by('kc.category_name')
			        ->order_by('kc.category_name', 'ASC')
			        ->get()
			        ->result();

			    foreach ($categories as $category) {
			        $totalRows++; // one row for the category header

			        $names = $this->db->select('bs.sname, bs.nid')
			            ->from('kg_subject_list_name bs')
			            ->where([
			                'bs.sgrade' => $grade,
			                'bs.sterm' => $chibt,
			                'bs.academicyear' => $max_year,
			                'bs.week' => $weekName,
			                'bs.scategory' =>$category->category_name
			            ])
			            ->order_by('bs.nid', 'ASC')
			            ->get()
			            ->result();

			        $totalRows += count($names); // one row per learning objective
			    }

			    // Now we have totalRows calculated dynamically for rowspan

			    $output .= '<tr>
			        <td rowspan="' . $totalRows . '">' . $weekLabel . '<br><br>ቀን: ---------</td>';

			    $output .= '</tr>';

	            $no = 1;
	            foreach ($categories as $category) {
	                $output .= '<tr>
	                    <th colspan="' . (3 + $totalKey) . '" style="background-color:#e3e3e3">'
	                    . $noCategory . ' - ' . $category->category_name . '</th>
	                </tr>';

	                // Fetch names/objectives under this category
	                $names = $this->db->select('bs.sname, bs.nid')
	                    ->from('kg_subject_list_name bs')
	                    ->where([
	                        'bs.sgrade' => $grade,
	                        'bs.sterm' => $chibt,
	                        'bs.academicyear' => $max_year,
	                        'bs.week' => $weekName,
	                        'bs.scategory' =>$category->category_name
	                    ])
	                    ->order_by('bs.nid', 'ASC')
	                    ->get()
	                    ->result();

	                foreach ($names as $sName) {
	                    $criteriaName = $sName->nid;
	                    $output .= '<tr>
	                        <td>' . $noCategory . '.' . $no . '</td>
	                        <td>' . $sName->sname . '</td>';

	                    foreach ($keys as $key) {
	                        $value_type = $key->value_name;
	                        $queryResult = $this->db->query("SELECT value FROM kg_subject_student_result 
	                            WHERE academicyear='$max_year' 
	                            AND stuid='$username' 
	                            AND criteria_name='$criteriaName' 
	                            AND quarter='$chibt' 
	                            AND result_period='$weekName' ");

	                        if ($queryResult->num_rows() > 0) {
	                            $f_result = $queryResult->row()->value;
	                            $output .= '<td class="text-center">'
	                                . ($f_result == $value_type ? '<i class="fas fa-check-circle"></i>' : '-') 
	                                . '</td>';
	                        } else {
	                            $output .= '<td class="text-center">-</td>';
	                        }
	                    }

	                    $output .= '</tr>';
	                    $no++;
	                }
	                $noCategory++;
	            }
	        }

	        $output .= '</tbody></table></div>';

	        // Evaluation keys
	        $output .= '<div class="grandKGReport-keys">
	            <u>የምዘና ቁልፎች (Evaluation Keys)</u><hr>';

	        foreach ($keys as $key) {
	            $output .= '<span>' . $key->value_name . ' (' . $key->value_percent . ') => ' . $key->value_desc . '</span><br>';
	        }

	        $output .= '</div></div></div></div>';
	    }

	    $output .= '</div>'; // Close container

	    return $output;
	}
	private function translate_week($week)
	{
	    $map = [
	        'Week 1' => '1ኛ ሳምንት',
	        'Week 2' => '2ኛ ሳምንት',
	        'Week 3' => '3ኛ ሳምንት',
	        'Week 4' => '4ኛ ሳምንት',
	        'Week 5' => '5ኛ ሳምንት',
	        'Week 6' => '6ኛ ሳምንት',
	        'Week 7' => '7ኛ ሳምንት',
	        'Week 8' => '8ኛ ሳምንት',
	        'Week 9' => '9ኛ ሳምንት',
	        'Week 10' => '10ኛ ሳምንት'
	    ];
	    return $map[$week] ?? $week;
	}