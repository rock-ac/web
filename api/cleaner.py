'''
    RockAnticheat - multicomplex SA:MP anti-cheat system
    with innovative technologies and infallible verdicts.
    
    Developed by savvin & 0Z0SK0

    (c) 2023
'''

import mysql.connector
import time
import datetime
import os


# MAIN SETTING
SQL_HOST     = "localhost"
SQL_USER     = "robot"
SQL_PASSWORD = "9z40RC!L1s%M6NwnjG3RH@Mf"
SQL_DB       = "rockac_dev"

connection = mysql.connector.connect(user=SQL_USER, password=SQL_PASSWORD, host=SQL_HOST, database=SQL_DB)
connection.autocommit = True

class CUser():
    def __init__(self, sessionID):
        self.sessionID = sessionID
        self.updatedTime = 0

    def iterSessions(self):
        self.query = (f"SELECT `dateUpdated` FROM `sessions` WHERE `sessionID` = '{self.sessionID}'")
        with connection.cursor(buffered=True) as self.cursor:
            self.cursor.execute(self.query)
            for self.session in self.cursor.fetchall():
                print(self.session[0])
                self.st = int(datetime.datetime.strptime(str(self.session[0]), "%Y-%m-%d %H:%M:%S").strftime("%s"))
                self.lt = time.time()
                if((self.lt-self.st) > 25):
                    self.query = (f"DELETE FROM `sessions` WHERE `sessionID` = '{self.sessionID}'")
                    with connection.cursor() as self.cursor:
                        self.cursor.execute(self.query)

                    os.remove(f'/var/www/html/api/sessions/{self.sessionID}/index.php')
                    os.rmdir(f'/var/www/html/api/sessions/{self.sessionID}')

                    self.query = (f"DELETE FROM `sessions_mac` WHERE `sessionID` = '{self.sessionID}'")
                    with connection.cursor(buffered=True) as self.cursor:
                        self.cursor.execute(self.query)

                    self.query = (f"DELETE FROM `sessions_hwid` WHERE `sessionID` = '{self.sessionID}'")
                    with connection.cursor(buffered=True) as self.cursor:
                        self.cursor.execute(self.query)

    def work(self):
        self.iterSessions()

if __name__ == '__main__':
    query = ("SELECT `sessionID` FROM `sessions`")
    with connection.cursor() as cursor:
        cursor.execute(query)
        for user in cursor.fetchall():
            cUser = CUser(user[0])
            cUser.work()

    connection.close()