[pwnable.kr] [pwn] fd
=====================

>##문제
>Mommy! what is a file descriptor in Linux?  
>ssh fd@pwnable.kr -p2222 (pw:guest)

![default](https://user-images.githubusercontent.com/35005298/34519547-e5960ee2-f0c7-11e7-882a-1f528650d7d0.PNG)

들어가보니, 파일 3개가 있었습니다.

fd파일의 소스코드로 보이는 fd.c파일을 분석해 봅시다!

*****

>##분석

소스코드는 다음과 같다

```c	
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
```

```c
	int main(int argc, char* argv[], char* envp[])
```
를 보면, argv[ ] 에 문자열을 입력받는 형식임을 알 수 있습니다.  
```c
	int fd = atoi( argv[1] ) - 0x1234;
```
는 입력받은 값을 정수형으로 변환한뒤 1234~16~(4660~10~) 를 뺀 값을 fd 변수에 넣는 코드입니다.  
```c
	len = read(fd, buf, 32);
```
fd 변수는 read 함수에서 파일 디스크립터로 활용될 것입니다.  
이때, fd 가 0 이된다면, 사용자가 앞으로 입력하는 값의 32byte 가 buf 에 저장됩니다.   
```c
	if(!strcmp("LETMEWIN\n", buf))  
```
buf 의 내용이 'LETMEWIN' 즉, 사용자가 입력하는 값이 'LETMEWIN' 이 된다면
flag 파일을 읽어주는 알고리즘 입니다.

따라서 정리를 해보자면, fd를 실행시킨후, 그 옆에 12번째 줄에서 fd의 값이
0이 되게 해주는 값을 입력해주면 됩니다!!

*****

>##실행결과

![default](https://user-images.githubusercontent.com/35005298/34519548-e5c39f56-f0c7-11e7-8dbf-b7d7daa36867.PNG)

flag : mommy! I think I know what a filedescriptor is!!
