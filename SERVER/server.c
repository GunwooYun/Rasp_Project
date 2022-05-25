#include<stdio.h>
#include<stdlib.h>
#include<errno.h>
#include<string.h>

#include<wiringPi.h>
#include<wiringSerial.h>

#include<mysql/mysql.h>

/* DB information */
static char *host = "localhost";
static char *user = "root";
static char *pass = "kcci";
static char *dbname = "homeDB";

/* USB Serial Information */
char device[] = "/dev/ttyACM0";
int fd;
unsigned long baud = 115200;

int main()
{

	/* Initialize Connection */
	MYSQL *conn;
	conn = mysql_init(NULL);
	int sql_index, flag = 0;
	char in_sql[200] = {0};
	int res = 0;

	/* Connect DB */
	if(!(mysql_real_connect(conn, host, user, pass, dbname, 0, NULL, 0))){
		fprintf(stderr, "ERROR : %s[%d]\n", mysql_error(conn), mysql_errno(conn));
		exit(1);
	}
	else printf("Connection Successful!!\n");

	char ser_buff[16] = {0};
	char data_buff[16] = {0};
	int index = 0, temp, humi, flame, cds, str_len;
	char *pArray[4] = {0}; // To push data (humi, temp, flame, cds)
	char *pToken;
	printf("Raspberry Start\n");
	fflush(stdout);
	if((fd = serialOpen(device, baud)) < 0){
		// Not found USB device
		fprintf(stderr, "Unable %s\n",strerror(errno));
		exit(1);
	}
	if(wiringPiSetup() == -1) return 1;
	while(1){
		if(serialDataAvail(fd)){
			ser_buff[index++] = serialGetchar(fd);
			if(ser_buff[index-1] == 'L'){
				flag = 1; // got data
				ser_buff[index-1] = '\0';
				str_len = strlen(ser_buff);
				printf("length : %d\n", str_len);
				printf("Recived string : %s\n", ser_buff);

				/* Check string length */
				if(str_len < 9 || str_len > 10 ){
					for(int i = 0; i <= str_len; i++)
						ser_buff[i] = 0;
					index = 0;
					continue;
				}

				/* parsing */
				pToken = strtok(ser_buff, ":");
				int i = 0;
				while(pToken != NULL){
					pArray[i++] = pToken;
					pToken = strtok(NULL, ":");
				}
				temp = atoi(pArray[0]);
				humi = atoi(pArray[1]);
				flame = atoi(pArray[2]);
				cds = atoi(pArray[3]);
				printf("temp = %d, humi = %d, flame = %d, cds = %d\n", temp, humi, flame, cds);

				/*Init value of arrary*/
				for(int i = 0; i <= str_len; i++)
					ser_buff[i] = 0;
				index = 0;
			}

			/* Execute SQL */
			if(temp < 100 && humi < 100){
				if(flag){
					sprintf(in_sql, "insert into room_state(ID, DATE, TIME, HUMI, TEMP, FLAME, CDS) values(null, curdate(), curtime(), %d, %d, %d, %d)", humi, temp, flame, cds);
					res = mysql_query(conn, in_sql);
					printf("res ==> %d\n", res);
					if(!res)
						printf("Inserted %lu rows\n", (unsigned int) mysql_affected_rows(conn));
					else
					{
						fprintf(stderr, "error : %s[%d]\n", mysql_error(conn), mysql_errno(conn));
						exit(1);
					}
				}
			}
		}
		flag = 0;
		fflush(stdout);
	}
	mysql_close(conn);
	return EXIT_SUCCESS;
}
