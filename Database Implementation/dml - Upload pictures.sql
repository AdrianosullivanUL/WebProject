/*
Notes:
1) for WAMP pictures much be put in c:\wamp64\tmp folder, service may also need to be run under your own account
2) mediumblob needed for pictures, some may still be too big
*/
use group05;
start transaction;
update user_profile set picture = load_file('c:/wamp64/tmp/man1.png') where id = 5;
update user_profile set picture = load_file('c:/wamp64/tmp/man2.png') where id = 6;
update user_profile set picture = load_file('c:/wamp64/tmp/man3.png') where id = 9;
update user_profile set picture = load_file('c:/wamp64/tmp/man4.png') where id = 10;
update user_profile set picture = load_file('c:/wamp64/tmp/man5.png') where id = 15;
update user_profile set picture = load_file('c:/wamp64/tmp/man6.png') where id = 16;
update user_profile set picture = load_file('c:/wamp64/tmp/man7.png') where id = 22;
update user_profile set picture = load_file('c:/wamp64/tmp/man8.png') where id = 25;
update user_profile set picture = load_file('c:/wamp64/tmp/man9.png') where id = 27;
update user_profile set picture = load_file('c:/wamp64/tmp/man10.png') where id = 31;
update user_profile set picture = load_file('c:/wamp64/tmp/man11.png') where id = 34;
update user_profile set picture = load_file('c:/wamp64/tmp/man12.png') where id = 45;
update user_profile set picture = load_file('c:/wamp64/tmp/man13.png') where id = 52;
update user_profile set picture = load_file('c:/wamp64/tmp/man14.png') where id = 53;


update user_profile set picture = load_file('c:/wamp64/tmp/woman1.png') where id = 7;
update user_profile set picture = load_file('c:/wamp64/tmp/woman2.png') where id = 8;
update user_profile set picture = load_file('c:/wamp64/tmp/woman3.png') where id = 11;
update user_profile set picture = load_file('c:/wamp64/tmp/woman4.png') where id = 12;
update user_profile set picture = load_file('c:/wamp64/tmp/woman5.png') where id = 13;
update user_profile set picture = load_file('c:/wamp64/tmp/woman6.png') where id = 14;
update user_profile set picture = load_file('c:/wamp64/tmp/woman7.png') where id = 17;
update user_profile set picture = load_file('c:/wamp64/tmp/woman8.png') where id = 18;
update user_profile set picture = load_file('c:/wamp64/tmp/woman9.png') where id = 19;
update user_profile set picture = load_file('c:/wamp64/tmp/woman0.png') where id = 20;
update user_profile set picture = load_file('c:/wamp64/tmp/woman11.png') where id = 21;
update user_profile set picture = load_file('c:/wamp64/tmp/woman12.png') where id = 23;
update user_profile set picture = load_file('c:/wamp64/tmp/woman13.png') where id = 24;
update user_profile set picture = load_file('c:/wamp64/tmp/woman14.png') where id = 26;
update user_profile set picture = load_file('c:/wamp64/tmp/woman15.png') where id = 28;

commit;

