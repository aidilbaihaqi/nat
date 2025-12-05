## nak pake project ni? do this : 

### setup project
1. git clone https://github.com/aidilbaihaqi/nyoba-uk.git 
2. cd nyoba-uk 

### setup env nya dlu kak
3. cp env .env
4. edit di file .env

### buat blank database dlu dengan nama "kuliah", baru jalanin : 
5. php spark migrate
6. php spark db:seed DatabaseSeeder


### jalanin server nya kalau migration udah berhasil tanpa error. 
7. php spark serve
8. done

kalau nak nengok akun admin, mahasiswa dan dosen.. CTRL + P, trus ketik "UserSeeder.php".. haaaa ada disitu semua lah benda tu.

# PERHATIAN
<p>kalau tuan dan puan ingin ngepush, buat branch baru. awas aja push di main, ketahuan di commit.. wajib tr eskrim aidil</p>
