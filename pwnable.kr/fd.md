<문제>

Mommy! what is a file descriptor in Linux?
ssh fd@pwnable.kr -p2222 (pw:guest)


![default](https://user-images.githubusercontent.com/35005298/34519547-e5960ee2-f0c7-11e7-882a-1f528650d7d0.PNG)

들어가보니, 파일 3개가 있었습니다.



![fd](https://user-images.githubusercontent.com/35005298/34519549-e5f30494-f0c7-11e7-9f0d-d4ef09792c6f.PNG)


이제 소스코드를 분석해 봅시다.

<분석>

#include <stdio.h>

#include <stdlib.h>

#include <string.h>

char buf[32];

int main(int argc, char* argv[], char* envp[]){

if(argc<2){

printf("pass argv[1] a number\n");

return 0;

}

int fd = atoi( argv[1] ) - 0x1234;

int len = 0;

len = read(fd, buf, 32);

if(!strcmp("LETMEWIN\n", buf)){

printf("good job :)\n");

system("/bin/cat flag");

exit(0);


}

printf("learn about Linux file IO\n");

return 0;

}

5번째 줄을 보면, fd를 실행하는 명령어 바로 옆에 문자를 입력받는 형식을 이용합니다.
예를들어, bash에 ./fd 1234를 입력하면, argv[1]에 1234가 입력되는 것입니다.
(참고로 argv[0]에는 파일의 경로, argc에는 전달되는 인수의 개수가 저장됩니다.)

10번째 줄에서는 입력받은 값을 정수형으로 변환하고, 1234(16진수)를 뺀 값을 fd변수에 넣습니다.
두줄 아래로 가보시면 아시겠지만, fd변수는 read함수에서 파일 디스크립터로 활용될 것입니다.

이때, fd가 0이된다면, 사용자가 앞으로 입력하는 값의 32byte가 buf에 저장되겠죠?(파일 생성 권한이 없기때문입니다!)
그 아래에서는 buf의 내용이 LETMEWIN즉, 사용자가 입력하는 값이 LETMEWIN이 되면,
flag를 읽어주는 알고리즘 입니다.

따라서 정리를 해보자면, fd를 실행시킨후, 그 옆에 12번째 줄에서 fd의 값이
0이 되게 해주는 값을 입력해주면 됩니다!!

<풀이>

16진수, 1234를 10진수로 바꾸면, 4660이 됩니다. 그렇다면, fd를 실행시킨후, 4660을 입력한다면, read함수를 실행
할 때 fd가 0이 되어 원하는 값을 buf에 넣을 수 있게 됩니다.
![default](https://user-images.githubusercontent.com/35005298/34519550-e62165be-f0c7-11e7-90e7-e080b237884b.PNG)

이렇게요!

이제 이 입력값에 LETMEWIN을 입력한다면?
![default](https://user-images.githubusercontent.com/35005298/34519548-e5c39f56-f0c7-11e7-8dbf-b7d7daa36867.PNG)

이렇게 flag의 내용이 보이게 됩니다. 