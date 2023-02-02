'''
    RockAnticheat - multicomplex SA:MP anti-cheat system
    with innovative technologies and infallible verdicts.
    
    Developed by savvin & 0Z0SK0

    (c) 2023
'''

import mysql.connector


# DEFINE`S
INJECTED_BLACKLISTED_MODULE =           "0x000001a"
CHANGED_BLACKLISTED_MEMORY_SEGMENT =    "0x000002c"
DETECTED_BLACKLISTED_PROCESS =          "0x000003a"
DETECTED_NEW_CLEO_FILE =                "0x000004a"
DETECTED_MEMORY_PATTERN =               "0x000005a"
BLACKLISTED_USER =                      "0x0001488"

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
        self.username = ""
        
        self.reason = ""

        self.isInjectedBlacklistedModule       = False
        self.isChangedBlacklistMemorySegment   = False
        self.isChangedBlacklistedMemorySegment = False
        self.isDetectedBlacklistedProcess      = False
        self.isCreatedNewCleoFile              = False
        self.isDetectedMemoryPattern           = False


    def waitCheater(self, user):
        # патлатый, я маму твою ибал
        self.query = (f"SELECT `lastSessionID` FROM `users` WHERE `username` = '{user}'")
        print(self.query)
        with connection.cursor(buffered=True) as self.cursor:
            self.cursor.execute(self.query)
            if(self.cursor.rowcount == 0):
                return False
            else:
                for self.u in self.cursor.fetchall():
                    self.dd = self.u[0]

                    self.query = (f"UPDATE `users` SET `banned` = '1', `ban_info` = 'ROBOT (AutoBan)', `ban_date` = NOW() WHERE `username` = '{user}'")
                    print(self.query)
                    with connection.cursor() as self.cursor:
                        self.cursor.execute(self.query)

                    with open(f'/var/www/html/api/sessions/{self.dd}/index.php', 'w') as f:
                        f.write(f"<?php echo '0x0001488'; ?>")    

    def checkBlacklistModule(self, name, base_address, hash):
        self.query = (f"SELECT `name` FROM `cblacklistmodules` WHERE `hash` = '{hash}'")
        with connection.cursor(buffered=True) as self.cursor:
            self.cursor.execute(self.query)
            if(self.cursor.rowcount == 0):
                return False
            else:
                for self.module in self.cursor.fetchall():
                    self.reason = self.module[0]
                    return True
    
    def checkWhiteListMemory(self, name, base_address, region_size):
        self.query = (f"SELECT id FROM cwhitelistmemory WHERE name = '{name}' AND `base_address` = '{base_address}' AND region_size = '{region_size}'")
        with connection.cursor(buffered=True) as self.cursor:
            self.cursor.execute(self.query)
            if(self.cursor.rowcount == 0):
                return False
            else:
                return True

    def checkBlackListMemory(self, name, base_address, region_size):
        self.query = (f"SELECT id FROM cwhitelistmemory WHERE name = '{name}' AND region_size = '{region_size}'")
        with connection.cursor(buffered=True) as self.cursor:
            self.cursor.execute(self.query)
            if(self.cursor.rowcount == 0):
                return False
            else:
                return True

    def checkWhiteListCleo(self, name, hash):
        self.query = (f"SELECT id FROM cwhitelistcleo WHERE filename = '{name}' AND `hash` = '{hash}'")
        with connection.cursor(buffered=True) as self.cursor:
            self.cursor.execute(self.query)
            if(self.cursor.rowcount == 0):
                return False
            else:
                return True

    def iterModules(self):
        self.query = (f"SELECT `filename`,`base_address`,`hash`,`username` FROM `detected_module` WHERE `sessionID` = '{self.sessionID}'")
        with connection.cursor(buffered=True) as self.cursor:
            self.cursor.execute(self.query)
            for self.module in self.cursor.fetchall():
                self.username = self.module[3]
                if(self.checkBlacklistModule(self.module[0], self.module[1], self.module[2])):
                    self.isInjectedBlacklistedModule = True

    def iterPattern(self):
        self.query = (f"SELECT `id` FROM `detected_process` WHERE `sessionID` = '{self.sessionID}'")
        with connection.cursor(buffered=True) as self.cursor:
            self.cursor.execute(self.query)
            if(self.cursor.rowcount != 0):
                self.isDetectedMemoryPattern = True

    def iterMemory(self):
        self.query = (f"SELECT `name`,`base_address`,`region_size` FROM `detected_memory` WHERE `sessionID` = '{self.sessionID}'")
        with connection.cursor(buffered=True) as self.cursor:
            self.cursor.execute(self.query)
            for self.memory in self.cursor.fetchall():
                if(self.checkBlackListMemory(self.memory[0], self.memory[1], self.memory[2])):
                    self.isChangedBlacklistMemorySegment = True 
    
    def iterCleo(self):
        self.query = (f"SELECT `filename`,`hash` FROM `detected_cleo` WHERE `sessionID` = '{self.sessionID}'")
        with connection.cursor(buffered=True) as self.cursor:
            self.cursor.execute(self.query)
            for self.cleo in self.cursor.fetchall():
                if(not self.checkWhiteListCleo(self.cleo[0], self.cleo[1])):
                    self.isCreatedNewCleoFile = True

    def performVerdict(self, code):
        if code == INJECTED_BLACKLISTED_MODULE:
            self.query = (f"UPDATE `users` SET `banned` = '1', `ban_info` = 'ROBOT (Injected Blacklisted Module - {self.reason})', `ban_date` = NOW() WHERE `username` = '{self.username}'")
            with connection.cursor() as self.cursor:
                self.cursor.execute(self.query)

        with open(f'/var/www/html/api/sessions/{self.sessionID}/index.php', 'w') as f:
            f.write(f"<?php echo '{code}'; ?>")  

    def getVerdict(self):
        if(self.isDetectedMemoryPattern):
            self.performVerdict(DETECTED_MEMORY_PATTERN)

        if(self.isInjectedBlacklistedModule):
            self.performVerdict(INJECTED_BLACKLISTED_MODULE)
        
        if(self.isChangedBlacklistedMemorySegment):
            self.performVerdict(CHANGED_BLACKLISTED_MEMORY_SEGMENT)
            
        if(self.isCreatedNewCleoFile):
            self.performVerdict(DETECTED_NEW_CLEO_FILE)

    def work(self):
        self.users = ['fickser', 'GoodGame', 'Lezgistan', '9m113']
        for self.user in self.users:
            self.waitCheater(self.user)

        self.iterModules()
        self.iterMemory()
        self.iterCleo()
        self.iterPattern()
        self.getVerdict()

if __name__ == '__main__':
    query = ("SELECT `sessionID` FROM `sessions`")
    with connection.cursor() as cursor:
        cursor.execute(query)
        for user in cursor.fetchall():
            cUser = CUser(user[0])
            cUser.work()

    connection.close()