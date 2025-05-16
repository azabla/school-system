import sys
import mysql.connector
from mysql.connector import Error
branch = sys.argv[1]
gradesec = sys.argv[2]
subject = sys.argv[3]
quarter = sys.argv[4]
year = sys.argv[5]
#print (year)
try:
    connection = mysql.connector.connect(host='localhost',
                                         database='sms',
                                         user='root',
                                         password='chuchajossy21')
    sql_select_Query = "select * from users where usertype='Student' and academicyear='" + year + "' and gradesec='" + gradesec + "' and status='Active' and isapproved='1' and branch='" + branch + "' "
    cursor = connection.cursor()
    cursor.execute(sql_select_Query)
    # get all records
    records = cursor.fetchall()
    html = '<table><tbody>'
    for row in records:
    	html += '<tr><td>' + row[0] + '</td></tr>'
    html += '</tbody></table>'
    print(html)
except Error as e:
    print("Error while connecting to MySQL", e)
finally:
    if connection.is_connected():
        cursor.close()
        connection.close()
        print("MySQL connection is closed")