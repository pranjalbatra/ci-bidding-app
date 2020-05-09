
const baseurl="https://spentigo.com";
const baseurlimage=baseurl+"/uploads/imgs/";
var home=Vue.component('home-page',{
    template:
    `<div class="main">
    <div class="header-wrap ">
        <router-link to="/find"><img class="filters " src="./assets/img/filters.svg " alt="filters-icon "></router-link>
        <img class="logo " src="./assets/img/logo.svg " alt="skippin-out-logo ">
        <img class="profile-thumbnail " :src="loginImage" alt="profile-image " @click="loginSelect()">
    </div>
    <div class="fav-wrap">
       <router-link to="/find" ><img class="add " src="./assets/img/add.png "></router-link>
        <img class="fav" v-for="recent in recent" :src="'/uploads/imgs/' + recent.logo" v-if="recent.logo.length != 0"  @click="restaurentSelect({business:recent})">
    </div>
    
    <div class="card " v-if="searched.length !='0'" v-for="business in businesses" @click="restaurentSelect({business:business})">
    <div class="header ">
        <div class="left-title ">
        <img  :src="'https://spentigo.com/uploads/imgs/' + business.logo" alt="thumbnail" v-if="business.logo.length != 0">
            <div class="text ">
                <div class="title ">{{business.profile_name}}</div>
                <div class="sub-title ">{{Math.round(business.distance_in_km,2  )}} KM</div>
            </div>
        </div>
        <div class="right-score" v-if="business.userPoints != 0">{{business.userPoints}}</div>
        <div class="right-score" v-if="business.userPoints == 0">+</div>
    </div>
    <div class="main-image"  >
    <img :src="'https://spentigo.com/uploads/imgs/' + business.image" alt="main image" v-if="business.image.length != 0">
    </div>
    <div class="offer-wrap">
        <div class="offer-content" v-for="offer in business.offers">
            <img class="offer " :src="'https://spentigo.com/uploads/imgs/' + offer.image" alt="thumbnail1" v-if="offer.image.length != 0">
            <img class="offer " src="assets/img/offers/offer.png" alt="thumbnail2" v-if="offer.image.length == 0">
            <div class="offer-title ">{{offer.title}}</div>
            <div class="offer-badge-count" v-if="offer.unlock_points != 0">{{offer.unlock_points}}</div>
        </div>
    </div>
        <div class="tagsBox1">
            <div class="tags" v-for="tag in business.categories" >
                <button class="tagsBtn" :style="{background:styleA}">{{tag}}</button>
            </div>
        </div>
    <div class="details-wrap ">
        <div class="details-wrap">
        <a href="" target="blank" @click="prevent" ><img class="icons " src="./assets/img/location.svg "></a>
            <div v-for="link in business.links" v-if="link.val">
            <a  :href="'tel:' + link.val" target="blank" @click="prevent"><img v-if="link.name == 'Phone'" class="icons " src="./assets/img/phone.svg "></a>
            <a v-if="link.name == 'Website'" :href="link.val" target="blank" @click="prevent"><img class="icons " src="./assets/img/website.svg "></a>
            <a v-if="link.name == 'Facebook'" :href="'https://facebook.com/' + link.val" target="blank" @click="prevent"><img class="icons " src="./assets/img/facebook.svg "></a>
            <a v-if="link.name == 'Youtube'" :href="'https://youtube.com/' + link.val" target="blank" @click="prevent"><img class="icons " src="./assets/img/youtube.svg "></a>
            <a v-if="link.name == 'Instagram'" :href="'http://instagram.com/' + link.val" target="blank" @click="prevent"><img class="icons " src="./assets/img/instagram.svg "></a>
            <a v-if="link.name == 'Whatsapp'" :href="'https://api.whatsapp.com/send?phone=' + link.val" target="blank" @click="prevent"><img class="icons " src="./assets/img/whatsapp.svg "></a>
            </div>
        </div>
    </div>
    </div>
    <div class="card " v-if="searched.length == '0'" v-for="business in filteredItems" @click="restaurentSelect({business:business})">
    <div class="header ">
        <div class="left-title ">
        <img  :src="'https://spentigo.com/uploads/imgs/' +business.logo" alt="thumbnail" v-if="business.logo.length != 0">
            <div class="text ">
                <div class="title ">{{business.profile_name}}</div>
                <div class="sub-title ">{{Math.round(business.distance_in_km,2  )}} KM</div>
            </div>
        </div>
        <div class="right-score" v-if="business.userPoints != 0">{{business.userPoints}}</div>
        <div class="right-score" v-if="business.userPoints == 0">+</div>
    </div>
    <div class="main-image"  >
    <img :src="'https://spentigo.com/uploads/imgs/' + business.image" alt="main image" v-if="business.image.length != 0">
    </div>
    <div class="offer-wrap">
        <div class="offer-content " v-for="offer in business.offers">
            <img class="offer " :src="'https://spentigo.com/uploads/imgs/' + offer.image" alt="thumbnail1" v-if="offer.image.length != 0">
            <img class="offer noofferimage" src="assets/img/offers/offer.png" alt="thumbnail2" v-if="offer.image.length == 0" >
            <div class="offer-title ">{{offer.title}}</div>
            <div class="offer-badge-count" v-if="offer.unlock_points != 0">{{offer.unlock_points}}</div>
        </div>
        <div class="offer-content " v-for="offer in business.events">
        <img class="offer " :src="'https://spentigo.com/uploads/imgs/' + offer.image" alt="thumbnail1" v-if="offer.image.length != 0">
        <img class="offer noeventimage" src="assets/img/offers/offer.png" alt="thumbnail2" v-if="offer.image.length == 0">
        <div class="offer-title ">{{offer.title}}</div>
    </div>
    </div>
        <div class="tagsBox1">
            <div class="tags" v-for="tag in business.categories" >
                <button class="tagsBtn" :style="{background:styleA}">{{tag}}</button>
            </div>
        </div>
    <div class="details-wrap ">
        <div class="details-wrap">
        <a :href="'https://www.google.com/maps/?q=' +business.lat + ',' + business.lon + '&z=4'" target="blank" @click="prevent" ><img class="icons " src="./assets/img/location.svg "></a>
            <div v-for="link in business.links" v-if="link.val">
            <a  :href="'tel:' + link.val" target="blank" @click="prevent"><img v-if="link.name == 'Phone'" class="icons " src="./assets/img/phone.svg "></a>
            <a v-if="link.name == 'Website'" :href="link.val" target="blank" @click="prevent"><img class="icons " src="./assets/img/website.svg "></a>
            <a v-if="link.name == 'Facebook'" :href="'https://facebook.com/' + link.val" target="blank" @click="prevent"><img class="icons " src="./assets/img/facebook.svg "></a>
            <a v-if="link.name == 'Youtube'" :href="'https://youtube.com/' + link.val" target="blank" @click="prevent"><img class="icons " src="./assets/img/youtube.svg "></a>
            <a v-if="link.name == 'Instagram'" :href="'https://instagram.com/' + link.val" target="blank" @click="prevent"><img class="icons " src="./assets/img/instagram.svg "></a>
            <a v-if="link.name == 'Whatsapp'" :href="'https://api.whatsapp.com/send?phone=' + link.val" target="blank" @click="prevent"><img class="icons " src="./assets/img/whatsapp.svg "></a>
            </div>
        </div>
    </div>
    </div>
    <div class="loadmore" v-if="businesses.length>counts">
    <button class="login-btn" @click="loadmore()">LOAD MORE</button>
    </div>
    </div>`,
    data:function(){
        return{
            searched:'',
           categoryid:'',
            businesses:'',
            loginImage:'',
            recent:'',
            userdata:'',
            lat:'',
            lon:'',
            count:0,
            counts:20,
            countlist:['2','4','6'],
            offerImage:['o1-100.jpg','o2-100.jpg','o3-100.jpg','o4-100.jpg','o5-100.jpg'],
            eventImage:['e1-100.jpg','e4-100.jpg','e3-100.jpg','e4-100.jpg','e5-100.jpg'],
            color:['#171851','#09161e','#9f2f2d','#0b2c3d','#4d7da6']
        }
    },computed: {
        filteredItems: function () {
          return this.businesses.slice(0,this.counts)
          },
       styleA:function(){
          return this.color[Math.floor(Math.random() * this.color.length)];
            // return 'background:' + rand;
        }
       },props:['data'],
    methods:{
        loadmore:function(){
            var vm =this;
            vm.counts=vm.counts+20;
        },
        prevent:function(){
            event.stopPropagation();
        },
        loginSelect:function(){
            if(localStorage.userdata){
                this.$router.push('profile');
            }else{
                this.$router.push('login');
            }
        },
       restaurentSelect:function({business}){
           localStorage.businessid=business.id;
        document.cookie="busiid=" + business.id;
           this.$router.push({path:'business',query:{id:business.id}});
       }
    },updated() {
        var tagLength=document.querySelectorAll('.tags').length;
        for(var k =0;k<tagLength;k++){
            var rand =this.color[Math.floor(Math.random() * this.color.length)];
            document.getElementsByClassName('tagsBtn')[k].style.background=rand;
        }
        var vm = this;
        var offerlength=document.querySelectorAll('.noofferimage').length;
        console.log(offerlength);
    for(var i=0;i<offerlength;i++){
        var j=i%5;
        document.getElementsByClassName('noofferimage')[i].src='assets/img/offers/'+vm.offerImage[j];
    }
    var eventlength=document.getElementsByClassName('noeventimage').length;
    for(var i=0;i<eventlength;i++){
        var j=i%5;
        document.getElementsByClassName('noeventimage')[i].src='assets/img/offers/'+vm.eventImage[j];
    }
    var tagLength=document.querySelectorAll('.tags').length;
    for(var k =0;k<tagLength;k++){
        var rand =this.color[Math.floor(Math.random() * this.color.length)];
        document.getElementsByClassName('tagsBtn')[k].style.background=rand;
    }

    },mounted:function(){
        var rand=this.color[Math.floor(Math.random() * this.color.length)];
        console.log(rand)
        var vm=this;
        if(localStorage.searched){
            
            vm.searched=localStorage.searched;
        }
        if(localStorage.tag){
            this.categoryid=localStorage.tag;
        }
        if(localStorage.userdata){
        this.userdata=JSON.parse(localStorage.userdata);
        }
        if(localStorage.lat && localStorage.lon){
            if(localStorage.userdata){
                var user=JSON.stringify({
                    lat:localStorage.lat,
                    lon:localStorage.lon,
                    user_id:vm.userdata.user_id,
                    api_key:vm.userdata.api_key,
                    count:'0',
                    category_id:vm.categoryid,
                    search:vm.searched
                });
                console.log(user)
                axios.post('https://spentigo.com/api/user/fetchUserHomePage',user,{headers:{'Content-type':'application/json'}}).then(
                    result=>{
                        console.log(result.data);
                        vm.businesses=result.data;
                        console.log(vm.businesses);
                        for(var i=0;i<vm.businesses.length;i++){
                            vm.businesses[i].links=JSON.parse(result.data[i].links) 
                            console.log(vm.businesses);
                        }
                        console.log(vm.businesses);
                    },error=>{
                        console.log(error.data);
                        console.log(error.message);
                    });
                    
    }else{
            var user=JSON.stringify({
                lat:localStorage.lat,
                lon:localStorage.lon,
                user_id:'0',
                api_key:"",
                count:'0',
                category_id:vm.categoryid,
                search:vm.searched
            });
            console.log(user)
            axios.post('https://spentigo.com/api/user/fetchUserHomePage',user,{headers:{'Content-type':'application/json'}}).then(
                result=>{
                    console.log(result.data);
                    localStorage.setItem('business',JSON.stringify(result.data));
                    vm.businesses=result.data;
                    for(var i=0;i<vm.businesses.length;i++){
                        vm.businesses[i].links=JSON.parse(result.data[i].links) 
                       
                    }
                    console.log(vm.businesses);
                },error=>{
                    console.log(error.data);
                    console.log(error.message);
                });
        }

        }
        else{
        navigator.geolocation.getCurrentPosition(locationHandler);
        function locationHandler(position)
        {
          var lat = position.coords.latitude;
          var lng = position.coords.longitude;
          console.log(lat)
          vm.lat = lat
          vm.lon =lng
          console.log(vm.lat);
    if(localStorage.userdata){
                var user=JSON.stringify({
                    lat:vm.lat,
                    lon:vm.lon,
                    user_id:vm.userdata.user_id,
                    api_key:vm.userdata.api_key,
                    count:'0',
                    category_id:vm.categoryid,
                    search:vm.searched
                });
                console.log(user)
                axios.post('https://spentigo.com/api/user/fetchUserHomePage',user,{headers:{'Content-type':'application/json'}}).then(
                    result=>{
                        console.log(result.data);
                        vm.businesses=result.data;
                        console.log(vm.businesses);
                        for(var i=0;i<vm.businesses.length;i++){
                            vm.businesses[i].links=JSON.parse(result.data[i].links) 
                            console.log(vm.businesses);
                        }
                        console.log(vm.businesses);
                    },error=>{
                        console.log(error.data);
                        console.log(error.message);
                    });
                    
    }else{
            var user=JSON.stringify({
                lat:vm.lat,
                lon:vm.lon,
                user_id:'0',
                api_key:"",
                count:'0',
                category_id:vm.categoryid,
                search:vm.searched
            });
            console.log(user)
            axios.post('https://spentigo.com/api/user/fetchUserHomePage',user,{headers:{'Content-type':'application/json'}}).then(
                result=>{
                    console.log(result.data);
                    localStorage.setItem('business',JSON.stringify(result.data));
                    vm.businesses=result.data;
                    for(var i=0;i<vm.businesses.length;i++){
                        vm.businesses[i].links=JSON.parse(result.data[i].links) 
                       
                    }
                    console.log( vm.businesses);
                },error=>{
                    console.log(error.data);
                    console.log(error.message);
                });
        }
        localStorage.removeItem('searched');
    
    }}
    if(localStorage.searched){
        localStorage.removeItem('searched');
    }
    if(localStorage.tag){
        localStorage.removeItem('tag');
    }
    if(localStorage.userdata){
        var user1=JSON.stringify({
            user_id:vm.userdata.user_id,
            api_key:vm.userdata.api_key
        });
        console.log(user1)
        axios.post('https://spentigo.com/api/user/recentVisits',user1,{headers:{'Content-type':'application/json'}}).then(
            result=>{
                console.log(result.data);
                localStorage.setItem('recentVisit',JSON.stringify(result.data));
                vm.recent=result.data;
            },error=>{
                console.log(error.data);
                console.log(error.message);
            });
    }else{
        var user1=JSON.stringify({
            user_id:'10',
            api_key:"abc"
        });
        console.log(user1)
        axios.post('https://spentigo.com/api/user/recentVisits',user1,{headers:{'Content-type':'application/json'}}).then(
            result=>{
                console.log(result.data);
                localStorage.setItem('recentVisit',JSON.stringify(result.data));
                vm.recent=result.data;
            },error=>{
                console.log(error.data);
                console.log(error.message);
            });
          }
      
    if(localStorage.loginId){
            this.loginImage=localStorage.loginImage;
    }else{
            this.loginImage='assets/img/user.png'
    }
        // localStorage.setItem('notification',JSON.stringify(this.notification));
        localStorage.setItem('restaurents',JSON.stringify(this.restaurents));
        localStorage.setItem('restImages',JSON.stringify(this.images));
        localStorage.setItem('visited',JSON.stringify(this.visited));
    console.log(this.businesses);
    
    }
   });
   
var login=Vue.component('login-page',{
    template:
    `<div class="main">
    <div class="login-body">
    <div class="login-header">
        <div class="left">
        <router-link to="/"><img src="./assets/img/Group 45.png" class="back" alt="back"></router-link>
        </div>
        <div class="heading-div "><h2 class="login-heading">Login</h2></div>
    </div>
    <div class=" login-box">
    <h4 class="pad25 login-text">PLEASE LOGIN TO CONTINUE</h4>
    <div class="login-combo">
       
        <input type="number"  class="login-input" placeholder="ENTER YOUR PHONE NUMBER" id="phone" >
    </div>
    <input type="otp" class="login-input" placeholder="O T P" id="otp">
    </div>
    <div class="login-button-div">
        <button class="login-btn" @click="login()" id="login">LOGIN</button>
        <button class="login-btn1" @click="verify()" id="verifyOtp">Verify</button>
        <button class="login-btn1" @click="resend()" id="resendOtp">Resend Otp</button>
    </div>
    <div class="login-bottom">
        <h4 class="grey-business addBussiness">ADD YOUR BUSINESS</h4>
        <h4 class="plinks grey">PRIVACY</h4>
        <h4 class="plinks grey">ABOUT US</h4>
    </div>
    </div>
</div>`
,data:function(){
    return{
        phone:'',
        otp:'',
    }
},
methods:{
login:function(){
    this.phone =document.getElementById('phone').value
    var user=JSON.stringify({
        phone:this.phone,
    });
    console.log(user);
    axios.post('/api/user/sendOtp',user,{headers:{'Content-type':'application/json'}}).then(
        result=>{
            console.log(result.data);
            console.log(result.status);
            phone.style.display="none";
            document.getElementById('otp').style.display="block";
            document.getElementById('login').style.display="none";
            document.getElementById('verifyOtp').style.display="block";
            document.getElementById('resendOtp').style.display="block";
        },error=>{
            console.log('Error');
            console.log(error.data);
            console.log(error.message);
        });  
    },resend(){
        var user=JSON.stringify({
            phone:this.phone,
        });
        console.log(user);
        axios.post('/api/user/sendOtp',user,{headers:{'Content-type':'application/json'}}).then(
            result=>{
                console.log(result.data);
                console.log(result.status);
                phone.style.display="none";
                document.getElementById('otp').style.display="block";
                document.getElementById('login').style.display="none";
                document.getElementById('verifyOtp').style.display="block";
            },error=>{
                console.log('Error');
                console.log(error.data);
                console.log(error.message);
            });  
    },
verify:function(){
    var otp=document.getElementById('otp').value;
    var user=JSON.stringify({
        phone:this.phone,
        otp:otp
    });
    axios.post('/api/user/verifyOtp',user,{headers:{'Content-type':'application/json'}}).then(
        result=>{
            console.log(result.data);
            console.log(result.status);
            if(result.data.user_id){
            localStorage.setItem('userdata',JSON.stringify(result.data));
            this.$router.push('/');
        }else{
            alert('Invalid OTP');
        }
        },error=>{
            console.log('Error');
            console.log(error.data);
            console.log(error.message);
        });
    
},
},mounted(){ 
    
    }
});
var history=Vue.component('history-page',{
    template:
    `<div class="main">
    <div class="login-body" >
    <div class="login-header">
        <div class="center">
        <router-link to="/profile"><img src="./assets/img/Group 45.png" class="back" alt="back"> </svg></router-link>
        </div>
        <div class="header-div">
        <h3 class="login-heading">HISTORY</h3>
        </div>
    </div>
    <div class="historyBody">
        <div class="history-box" v-for="history in historys">
        <div class="header2">
    <div class="left-title ">
    <img :src="'/uploads/imgs/' + history.logo" alt="history" class="historyImage">
        <div class="text ">
            <div class="title "><h3 class="roboto">{{history.profile_name}}</h3></div>
            <div class="sub-title "> <h5 class="roboto historyTime">{{history.timestamp}}</h5></div>
        </div>
    </div>
    <div class="right-score "><h3>{{history.points_given}}</h3></div>
        </div>
        <h3 class="roboto historyText">You claimed an offer <span class="historyTitle">{{history.title}}</span> on {{history.timestamp}}</h3>
        </div>
    </div>
    </div>
    </div>`,
    data:function(){
        return{
            historys:'',
            userdata:'',
        }
    },
    mounted(){
        
        this.userdata=JSON.parse(localStorage.userdata);
        var user=JSON.stringify({
            user_id:this.userdata.user_id,
            api_key:this.userdata.api_key
        });
        console.log(user)
        axios.post('/api/user/getUserHistory',user,{headers:{'Content-type':'application/json'}}).then(
            result=>{
                localStorage.setItem('history',JSON.stringify(result.data));
                console.log(result.data);
                this.historys=result.data;
            },error=>{
                console.log(error.message);
            });
    },
    watch:{
        historys:function(){
            return this.historys;
        },
        updated() {
            console.log('updated')
        },
    }
});
var find=Vue.component('find-page',{
    template:
    `<div class="main">
    <div class="login-body" >
    <div class="find-header">
        <div class="left">
        <router-link to="/"><img src="./assets/img/Group 45.png" class="back" alt="back">
        </router-link>
        </div>
        <div class="heading ">
        <h3 class="login-heading">Find</h3>
        </div>
    </div>
    <div class="historyBody">
        <div class="header2">
            <div class=" locationImgDiv"><img src="assets/img/Group 53.png" class="locationImg" alt="location" @click="currentLocation"></div>
                <div class="right-score locationInputDiv">
                    <form class="formIn" autocomplete="off">
                        <input class="login-input1" id="locaddress" placeholder="LOCATION" v-on:keyup="getLocation" v-on:focusout='findgeo'>
                       
                    </form>
                </div>
            </div>
        <div class="row searchTag">
        <form class="formIn" autocomplete="off">
        <input class="login-input" id="searchTags" placeholder="SEARCH TAGS" v-model="tag" v-on:keyup="search">
        <div class="arrowBtn">
        <img src="assets/img/Group 52.png" alt="search" @click="searchClick()" class="cursor">
        </div>
        </form>
        </div>
        <div>
        <div class="feedback-box header2" v-for="restaurent in restaurent">
            <div class="left-title"><img :src="restaurent.thumbnail" class="feedback-thumbnail">
            <div class="text">
                <h3 class="feedback-title roboto" @click="routetorestaurent({selected:restaurent})">{{restaurent.title}}</h3>
            </div>
            </div>
        </div>
        </div>
        <div class="feedback-box" id="nomatch1"><h3 class=" roboto" id="nomatch"></h3></div>
        <div class="tagsBox">
            <div class="tags" v-for="tag in tags">
                <button @click="search1({tag:tag.id})"  class="tagsBtn" :style="{background:styleA}">{{tag.title}}</button>
            </div>
        </div>
    </div>
    </div>
    </div>`,
    data:function(){
        return{
            tag:'',
            tags:[],
            restaurents:'',
            restaurent:[],
            searched:[],
            color:['#171851','#09161e','#9f2f2d','#0b2c3d','#4d7da6']
        }
    },
    mounted:function(){
        if(localStorage.business){
            this.restaurents=JSON.parse(localStorage.business);
        }
        
        axios.post('/api/user/getCategoryList',{headers:{'Content-type':'application/json'}}).then(
            result=>{
                localStorage.setItem('categoryList',JSON.stringify(result.data));
                console.log(result.data);
                this.tags=result.data;
            },error=>{
                console.log(error.message);
            });
    },updated(){
        var tagLength=document.querySelectorAll('.tags').length;
        for(var k =0;k<tagLength;k++){
            var rand =this.color[Math.floor(Math.random() * this.color.length)];
            document.getElementsByClassName('tagsBtn')[k].style.background=rand;
        }
    },
    methods:{
        currentLocation:function(){
           
            navigator.geolocation.getCurrentPosition(locationHandler);
            function locationHandler(position)
            {
              var lat = position.coords.latitude;
              var lng = position.coords.longitude;
              localStorage.lat=lat;
              localStorage.lon=lng;
              this.$router.push('/');
            }
        },
        getLocation:function (e) {
            var vm =this;
            if (e.keyCode === 13) {
                var address=document.getElementById('locaddress').value;
            function showResult(result) {
                localStorage.lat=result.geometry.location.lat();
                 localStorage.lon=result.geometry.location.lng();
                 vm.$router.push('/');
             }
             function getLatitudeLongitude(callback, address) {
                address = address || 'Delhi, Noida';
                var geocoder = new google.maps.Geocoder();
                if (geocoder) {
                    geocoder.geocode({
                        'address': address
                    }, function (results, status) {
                        if (status == google.maps.GeocoderStatus.OK) {
                            callback(results[0]);
                        }
                    });
                }
            }
            getLatitudeLongitude(showResult, address)
        }else{
            var input = document.getElementById('locaddress');
            var autocomplete = new google.maps.places.Autocomplete(input);
    }
        },findgeo:function(){
            var vm =this;
            var address=document.getElementById('locaddress').value;
            function showResult(result) {
                localStorage.lat=result.geometry.location.lat();
                 localStorage.lon=result.geometry.location.lng();
                 vm.$router.push('/');
                }
                function getLatitudeLongitude(callback, address) {
                   address = address || 'Delhi, Noida';
                   var geocoder = new google.maps.Geocoder();
                   if (geocoder) {
                       geocoder.geocode({
                           'address': address
                       }, function (results, status) {
                           if (status == google.maps.GeocoderStatus.OK) {
                               callback(results[0]);
                           }
                       });
                   }
               }
               getLatitudeLongitude(showResult, address)
        },
        findlocation:function(position){
           var lat=position.coords.latitude;
           var lon=position.coords.longitude
           console.log('lat=' + lat +'lon='+lon)
           
        },
        search: function(e) {
            if (e.keyCode === 13) {
               var tag = document.getElementById('searchTags').value;
               this.searched=tag;
            localStorage.searched=this.searched;
            console.log(this.searched);
            localStorage.removeItem('tag');
            this.$router.push('/');
        }
        },searchClick:function(){
            var tag = document.getElementById('searchTags').value;
            this.searched=tag;
         localStorage.searched=this.searched;
         console.log(this.searched);
         localStorage.removeItem('tag');
         this.$router.push('/');
        }, search1: function({tag}) {
             
    this.searched=tag;
        if(this.searched.length>=1){
            localStorage.tag=tag;
            localStorage.removeItem('searched');
            this.$router.push('/');
        }
        },

    routetorestaurent:function({selected}){
        localStorage.setItem('selectedRestaurent',JSON.stringify(selected));
        this.$router.push('business');
    }
    },
    computed:{
        styleA:function(){
            return this.color[Math.floor(Math.random() * this.color.length)];
              // return 'background:' + rand;
          }
    }
});
var feedback= Vue.component('feedback-page',{
    template:`<div class="main" id="profileBody">
    <div class="header1">
    <div class="left-title ">
    <router-link to="/profile"><img src="./assets/img/Group 45.png" class="back" alt="back"></router-link>
    <img :src="'/uploads/imgs/' + restaurents.logo" alt="thumbnail ">
        <div class="text ">
            <div class="title ">{{restaurents.profile_name}}</div>
        </div>
    </div>
</div>
    <div class="card1" >
    <div class="main-image"  >
    <img :src="'/uploads/imgs/' + restaurents.image" alt="main image">
    </div>
    <div class="row">
        <h3 class="rateText roboto">How likely is it that you would recommend <span class="uppercase">{{restaurents.profile_name}}</span> to a friend or colleague?</h3>
    </div>
    <div class="row scale">
    <input type="range" min="1" max="10" step="1" value="10" id="rate" v-on:change="rate()">
    <h3 id="rating" class="roboto" >{{rates}}</h3>
    </div>
    <div class="addMessage">
    <textarea class="login-input " placeholder="ADD A MESSAGE" rows="10" id="comment"></textarea>
    </div>
    <div class="submitBitch">
        <button class="submitBitchBtn roboto" @click="submitFeedback">SUBMIT</button>
    </div>
    </div>
    </div>`,
    data:function(){
        return{
            restaurents:[],
            restaurent:'',
            rates:10,
            userdata:'',
        }
    },created(){
        this.restaurents=JSON.parse(localStorage.feedbackRestaurent);
    },mounted:function(){
        var viewh=document.getElementById('profileBody').clientHeight;
        if(viewh<window.innerHeight){
            document.getElementById('profileBody').style.height="100vh";
        }else{
            document.getElementById('profileBody').style.height="100%";
            }  
            this.userdata=JSON.parse(localStorage.userdata);
    },
    methods:{
        rate:function(){
            var rate=document.getElementById('rate').value;
            this.rates=rate;
        },
        submitFeedback:function(){
            var comment=document.getElementById('comment').value;
            var user=JSON.stringify({
                user_id:this.userdata.user_id,
                api_key:this.userdata.api_key,
                visit_id:this.restaurents.id,
                rating:this.rates,
                comment:comment,
                client_id:this.restaurents.client_id
            });
            console.log(user)
            axios.post('/api/user/sendFeedback',user,{headers:{'Content-type':'application/json'}}).then(
                result=>{
                    localStorage.setItem('feedback3',JSON.stringify(result.data));
                    console.log(result.data);
                    // this.historys=result.data;
                    this.$route.push('/');
                },error=>{
                    console.log(error.message);
                });
        }
    }
});
var profile=Vue.component('profile-page',{
    template:
    `<div class="main" id="profileBody">
    <div class="login-body" >
    <div class="login-header">
        <div class=" left ">
        <router-link to="/"><img src="./assets/img/Group 45.png" class="back" alt="back"></router-link><a></a>
        </div>
        <div class="header-div">
        <h2 class="login-heading1">{{profiledata.name}}</h2>
        <h5 class="profile-subheader">{{profiledata.phone}} &nbsp;&nbsp;{{email}}</h5>
        </div>
    </div>
    <div class="profile-box center">
    
    <div class="row">
    <router-link to="/history" class="history-btn" v-if="userdata.length != 0">VIEW HISTORY</router-link>
    </div>
    <div>
    <div class="header2 feedback-box " v-for="restaurent in feedbacks">
        <div class="left-title"><img :src="'uploads/imgs/' + restaurent.logo" class="feedback-thumbnail">
            <div class="text">
                <h3 class="feedback-title roboto" @click="routetorestaurent({selected:restaurent})">{{restaurent.profile_name}}</h3>
            </div>
        </div>
        <div class="right-score">
            <h3 class="feedback roboto" @click="feedback({restaurent:restaurent})">GIVE FEEDBACK</h3>
        </div>
    </div>
    </div>
    <div class="feedback-box1" v-for="notification in notifications">
        <div v-if="notification.unread==1" class="row">
            <div class="col11 left roboto"><h3 class="notificationText">{{notification.msg}}.</h3></div>
            <div class="col1" v-if="notification.unread==1"><img src="assets/img/Ellipse 8.png" class="dot"></div>
        </div>
        <div v-else class="row">
            <div class="col11 left roboto"><h3 class="notificationTextOld ">{{notification.msg}}</h3></div>
            
        </div>
    </div>
    <h3 class="addBussiness left roboto">ADD YOUR BUSINESS..</h3>
    <div class="links">
    <h3 class="grey roboto">PRIVACY</h3>
    <h3 class="grey roboto">UPDATE</h3>
    <h3 class="grey roboto" @click="logout()">LOGOUT</h3>
    </div>
    </div>
    
    </div>
    </div>`,
    data:function(){
        return{
           profiledata:'',
            email:'',
            code:'',
            image:'',
            restaurents:'',
            notifications:'',
            userdata:'',
            feedbacks:''
        }
    },
    mounted:function(){
        this.userdata=JSON.parse(localStorage.userdata);
        this.name=localStorage.name;
        this.number=localStorage.number;
        this.email=localStorage.email;
        this.code=localStorage.code;
        this.image=localStorage.loginImage;
        // this.notifications=JSON.parse(localStorage.notification);
        // this.restaurents=JSON.parse(localStorage.visited);
        var user=JSON.stringify({
            user_id:this.userdata.user_id,
            api_key:this.userdata.api_key
        });
        console.log(user)
        axios.post('/api/user/getUserProfile',user,{headers:{'Content-type':'application/json'}}).then(
            result=>{
                localStorage.setItem('userProfile',JSON.stringify(result.data));
                this.profiledata=result.data[0];
                console.log(this.profiledata);
                
            },error=>{
                console.log(error.message);
            });
            var user1=JSON.stringify({
                user_id:this.userdata.user_id,
                api_key:this.userdata.api_key
            });
            console.log(user1)
            axios.post('/api/user/getUserFeedbacks',user1,{headers:{'Content-type':'application/json'}}).then(
                result=>{
                    console.log(result.data);
                    localStorage.setItem('feedbacks',JSON.stringify(result.data));
                    this.feedbacks=result.data;
                },error=>{
                    console.log(error.message);
                });
                var user2=JSON.stringify({
                    user_id:this.userdata.user_id,
                    api_key:this.userdata.api_key
                });
                console.log(user2)
                axios.post('/api/user/getUserNotifications',user2,{headers:{'Content-type':'application/json'}}).then(
                    result=>{
                        console.log(result.data);
                        localStorage.setItem('notifications',JSON.stringify(result.data));
                        this.notifications=result.data;
                    },error=>{
                        console.log(error.message);
                    });
                
        var viewh=document.getElementById('profileBody').clientHeight;
    if(viewh<window.innerHeight){
        document.getElementById('profileBody').style.height="100vh";
    }else{
        document.getElementById('profileBody').style.height="100%";
        } 
    },
    methods:{
        logout:function(){
            var user=JSON.stringify({
                user_id:this.userdata.user_id,
                api_key:this.userdata.api_key
            });
            console.log(user)
            axios.post('/api/user/logout',user,{headers:{'Content-type':'application/json'}}).then(
                result=>{
                    localStorage.clear();
                    this.$router.push('/');
                },error=>{
                    console.log(error.message);
                });
           
        },
        feedback:function({restaurent}){
            localStorage.setItem('feedbackRestaurent',JSON.stringify(restaurent));
            this.$router.push('feedback');
        },
        routetorestaurent:function({selected}){
            localStorage.setItem('selectedRestaurent',JSON.stringify(selected));
            this.$router.push('business');
        }
    }
});

var business=Vue.component('restaurent-page',{
    template:
    `<div class="main" id="profileBody">
    <div class="header1 " >
        <div class="left-title ">
        <router-link to="/" @click="clearRestaurents()"><img src="./assets/img/Group 45.png" class="back" alt="back"> </router-link>
        <img :src="'https://spentigo.com/uploads/imgs/' + clientData.logo" alt="thumbnail " v-if="clientData.length !='0'">    
        <div class="text" >
                <div class="title " v-if="clientData.length !='0'">{{clientData.profile_name}}</div>
                <div class="sub-title " v-if="clientData.length !='0'">{{Math.round(clientData.distance_in_km)}} KM</div>
            </div>
        </div>
        <div class="userPoints "><span class="refer"  @click="referfriend()" v-if="userdata.length !=0 && restaurents.userPoints != 0">Refer</span> &nbsp; <span class="cornerscore" v-if="restaurents.userPoints != 0">{{restaurents.userPoints}}</span><span class="cornerscore" v-if="restaurents.userPoints == 0">+</span></div>
    </div>
    <div class="card1" >
    <div class="main-image"  >
    <img  :src="'https://spentigo.com/uploads/imgs/' + clientData.image" alt="main image" v-if="clientData.length !='0'">
    </div>
    <div class="tagsBox1">
            <div class="tags" v-for="tag in clientData.categories">
                <button  class="tagsBtn" :style="{background:styleA}" >{{tag}}</button>
            </div>
        </div>
    <div class="offer-container">
        <div class="offerbox offerboxm" v-for="offer in restaurents.offers">
            <div class="restaurentBox">
                <div class="first-box ">
                <img class="offerimg" :src="'https://spentigo.com/uploads/imgs/' + offer.image" alt="thumbnail1" v-if="offer.image.length != 0">
                <img class="offerimg noofferimage" src="assets/img/offers/offer.png" alt="thumbnail2" v-if="offer.image.length == 0">
                    <div class="offerbadge-count1" v-if="offer.unlock_points != 0">{{offer.unlock_points}} <img src="assets/img/lock.png" alt="lock" v-if="restaurents.userPoints < offer.unlock_points" class="lock"></div>
                   
                </div>
                <div class="second-box">
                    <h5 class="offerTitle roboto">{{offer.title}}</h5>
                    <h5 class="offerText roboto">{{offer.desc}}</h5>
                </div>
                <div class="third-box">
                    <h5 class="offerTitle roboto" v-if="offer.offer_points != 0" >+{{offer.offer_points}}</h5>
                </div>
            </div>
            <div class="dates1">
                <h4 class="offerTitle roboto" v-if="restaurents.userPoints < offer.unlock_points">TO BE UNLOCKED AT {{offer.unlock_points}} POINTS</h4>
                 <h5 class="offerTitle roboto" v-if="offer.forever=='1'">EVERYDAY 
                    {{offer.str_time}} 
                    <span v-if="offer.str_time != '' && offer.end_time != ''"> to </span> 
                    <span v-if="offer.str_time != '' && offer.end_time == ''"> Onwards </span> 
                    {{offer.end_time}} 
                </h5>
                <h5 class="offerTitle roboto" v-if="offer.forever=='0'"> 
                    {{offer.str_date}}
                    <span v-if="offer.str_date != '' && offer.end_date != offer.str_date"> to {{offer.end_date}}</span> 
                    <span v-if="offer.str_time != ''">, </span>
                    {{offer.str_time}} 
                     <span v-if="offer.str_time != '' && offer.end_time != ''"> to </span> 
                    <span v-if="offer.str_time != '' && offer.end_time == ''"> Onwards </span> 
                    {{offer.end_time}} 
                </h5>
            </div>
        </div>
    </div>
    <div class="offer-container">
        <div class="offerbox offerboxm" v-for="event in restaurents.events">
            <div class="restaurentBox">
                <div class="first-box ">
                <img v-if="event.image.length !=0" :src="'https://spentigo.com/uploads/imgs/' + event.image" alt="offer img" class="offerimg" >
                    <img :src="'https://spentigo.com/assets/img/offers/e4-100.jpg' " alt="offer img" class="offerimg noeventimage" v-if="event.image.length==0">
                                    </div>
                <div class="second-box">
                    <h5 class="offerTitle roboto">{{event.title}}</h5>
                    <h5 class="offerText roboto">{{event.desc}}</h5>
                </div>
            </div>
            <div class="dates1" >
                <h5 class="offerTitle roboto">
                    {{event.str_date}}
                    <span v-if="event.str_date != '' && event.end_date != event.str_date"> to {{event.end_date}}</span> 
                    <span v-if="event.str_time != ''">, </span>
                    {{event.str_time}} 
                     <span v-if="event.str_time != '' && event.end_time != ''"> to </span> 
                    <span v-if="event.str_time != '' && event.end_time == ''"> Onwards </span> 
                    {{event.end_time}} 
                </h5>
            </div>
        </div>
    </div>
    <div >
    <div class="desc"><h3 class="offerTitle">{{clientData.description}}</h3></div>
        <div class="row socialdetail roboto">
            
            <a class="socialLink" :href="'https://www.google.com/maps/?q=' +clientData.lat + ',' + clientData.lon + '&z=4'" target="blank" @click="prevent" ><img class="icons locicons" src="./assets/img/location.svg "/> <h2 class="socialText locText">LOCATION</h2></a>
        </div>
            <div v-for="social in clientData.links" class="social">
                <div class="row socialdetail roboto" v-if="social.name == 'Phone' && social.val != '' ">
                <a class="socialLink"  :href="'tel:' + social.val" target="blank" @click="prevent"> <img class="icons " src="./assets/img/phone.svg "><h2 class="socialText">
               
                {{social.val}}</h2></a>
                </div>
                <div class="row socialdetail roboto" v-if="social.name == 'Website' && social.val != '' ">
                <a class="socialLink" v-if="social.name == 'Website'" :href=" social.val" target="blank" @click="prevent"><img class="icons " src="./assets/img/website.svg ">
                <h2 class="socialText">{{social.val}}</h2>
                </a>
                </div>
                <div class="row socialdetail roboto" v-if="social.name == 'Facebook' && social.val != '' ">
                <a class="socialLink" v-if="social.name == 'Facebook'" :href="'https://facebook.com/' + social.val" target="blank" @click="prevent">
                <img class="icons " src="./assets/img/facebook.svg ">
                <h2 class="socialText">{{social.val}}</h2>
                </a>
                </div>
                <div class="row socialdetail roboto" v-if="social.name == 'Youtube' && social.val != '' ">
                <a class="socialLink" v-if="social.name == 'Youtube'" :href="'https://youtube.com/' + social.val" target="blank" @click="prevent">
                <img class="icons " src="./assets/img/youtube.svg ">
                <h2 class="socialText">{{social.val}}</h2>
                </a>
                </div>
                <div class="row socialdetail roboto" v-if="social.name == 'Instagram' && social.val != '' ">
                <a class="socialLink" v-if="social.name == 'Instagram'" :href="'https://instagram.com/' + social.val" target="blank" @click="prevent">
                <img class="icons " src="./assets/img/instagram.svg ">
                <h2 class="socialText">{{social.val}}</h2>
                </a>
                </div>
                <div class="row socialdetail roboto" v-if="social.name == 'Whatsapp' && social.val != '' ">
                <a class="socialLink" v-if="social.name == 'Whatsapp'" :href="'https://api.whatsapp.com/send?phone=' + social.val" target="blank" @click="prevent">
                <img class="icons " src="./assets/img/whatsapp.svg ">
                <h2 class="socialText">{{social.val}}</h2>
                </a>
                </div>
            </div>
            </div>
            
        </div>
        <div id="modal1" class="roboto">
        <div class="modal-document">
            <div class="modal-header">
                <h3>REFER FRIEND</h3>
            </div>
            <div class="modal-body">
            <input type="number" class="login-input" placeholder="ENTER YOUR FRIEND MOBILE NUMBER" id="friendNumber">
            <h4 class="grtext roboto" id="succtext"></h4>
            </div>
            <div class="modal-bottom" >
            <div class="send cursor" @click="sendRefer()">
             SEND</div>
             <div class="close cursor" @click="close">CLOSE</div>
            </div>
        </div>
    </div>
    </div>`,
    data:function(){
        return{
            clientData:'',
            restaurents:[],
            restaurent:'',
            point:'',
            userdata:'',
            lat:'',
            lon:'',
            offerImage:['o1-100.jpg','o2-100.jpg','o3-100.jpg','o4-100.jpg','o5-100.jpg'],
            eventImage:['e1-100.jpg','e4-100.jpg','e3-100.jpg','e4-100.jpg','e5-100.jpg'],
            color:['#171851','#09161e','#9f2f2d','#0b2c3d','#4d7da6']
        }
    },computed:{
        styleA:function(){
            return this.color[Math.floor(Math.random() * this.color.length)];
              // return 'background:' + rand;
          }
    },created(){
        if(this.$route.query.id){
            this.restaurent=this.$route.query.id;
        }else{
        this.restaurent= localStorage.businessid;
    }
    },beforeMount() {
    
    }, mounted:function(){
        var vm=this;
        navigator.geolocation.getCurrentPosition(locationHandler);
        function locationHandler(position)
        {
          var lat = position.coords.latitude;
          var lng = position.coords.longitude;
          console.log(lat)
          vm.lat = lat;
          console.log(vm.lat)
          vm.lon =lng;
          if(localStorage.userdata){
            vm.userdata=JSON.parse(localStorage.userdata);
            console.log(vm.userdata)
            var user1=JSON.stringify({
                lat:vm.lat,
                lon:vm.lon,
                client_id:vm.restaurent,
                user_id:vm.userdata.user_id,
                api_key:vm.userdata.api_key,
            });
            console.log(user1);
            axios.post('https://spentigo.com/api/user/getClientPage',user1,{headers:{'Content-type':'application/json'}}).then(
             result=>{
                 vm.restaurents=result.data;
                 console.log(result.data);
                 localStorage.setItem('sbusiness',JSON.stringify(result.data));
                 console.log(vm.restaurents.clientData);
                 vm.clientData=vm.restaurents.clientData;
                         vm.clientData.links=JSON.parse(result.data.clientData.links) ;
                         console.log(vm.restaurents.clientData.links)
     
                 },error=>{
                     console.log(error.data);
                     console.log(error.message);
                 });
        }else{
            var user1=JSON.stringify({
                lat:vm.lat,
                lon:vm.lon,
                client_id:vm.restaurent,
                user_id:'0',
                api_key:" ",
            });
            console.log(user1);
            axios.post('https://spentigo.com/api/user/getClientPage',user1,{headers:{'Content-type':'application/json'}}).then(
             result=>{
                 vm.restaurents=result.data;
                 localStorage.setItem('sbusiness',JSON.stringify(result.data));
                 console.log(vm.restaurents.clientData);
                 vm.clientData=vm.restaurents.clientData;
                         vm.clientData.links=JSON.parse(result.data.clientData.links) ;
                         console.log(vm.restaurents.clientData.links)
     
                 },error=>{
                     console.log(error.data);
                     console.log(error.message);
                 });
        }

        }
        var modal1 = document.getElementById('modal1');
		window.onclick = function(event) {
			if (event.target == modal1) {
				modal1.style.display = "none";
			}}
        // var viewh=document.getElementById('profileBody').clientHeight;
               var lat=Math.round(this.restaurent.lat);
        var lon=Math.round(this.restaurent.lon);
        // this.restaurents=JSON.parse(localStorage.sbusiness);
        lat=toString(lat);
        lon=toString(lon);
      
        console.log(this.$route.query.id);
    },watch:{
        clientData:function(){
            console.log('hi');
            var vm = this;
                var offerlength=document.querySelectorAll('.noofferimage').length;
            console.log(offerlength)
            console.log(vm.offerImage)
            for(var i=0;i<offerlength;i++){
                var j=i%5;
                document.getElementsByClassName('noofferimage')[i].src='assets/img/offers/'+vm.offerImage[j];
            }
            var eventlength=document.getElementsByClassName('noeventimage').length;
            for(var i=0;i<eventlength;i++){
                var j=i%5;
                document.getElementsByClassName('noeventimage')[i].src='assets/img/offers/'+vm.eventImage[j];
            }
        }
    },updated() {
        console.log('hi2');
        var vm = this;
        var offerlength=document.querySelectorAll('.noofferimage').length;
    for(var i=0;i<offerlength;i++){
        var j=i%5;
        document.getElementsByClassName('noofferimage')[i].src='assets/img/offers/'+vm.offerImage[j];
    }
    var eventlength=document.getElementsByClassName('noeventimage').length;
    for(var i=0;i<eventlength;i++){
        var j=i%5;
        document.getElementsByClassName('noeventimage')[i].src='assets/img/offers/'+vm.eventImage[j];
    }
    },
    methods:{
        clearRestaurents:function(){
        },
        referfriend:function(){
                document.getElementById('modal1').style.display='block';
        },
        closerefer:function(){
            document.getElementById('modal1').style.display='none';
        },
        close:function(){
            document.getElementById('modal1').style.display="none";
        },
        sendRefer:function(){
            var number=document.getElementById('friendNumber').value;
            console.log(number);
            var user1=JSON.stringify({
                friend_phone:number,
                client_id:this.restaurent,
                user_id:this.userdata.user_id,
                api_key:this.userdata.api_key,
            });
            axios.post('https://spentigo.com/api/user/referFriend',user1,{headers:{'Content-type':'application/json'}}).then(
             result=>{
                 console.log(result.data);
                  document.getElementById('succtext').innerHTML=result.data;
                  document.getElementById('friendNumber').value=" ";
                 },error=>{
                     console.log(error.data);
                     console.log(error.message);
                 });
        },prevent:function(){
            event.stopPropagation();
        },
    }
    });
   var routes = [
	{
  	path: '/',
      component: home,
      name:'home',
      props:true,
  },
  {
      	path: '/login',
  component: login,
    },
    {
        path: '/profile',
component: profile,
  },
  {
    path: '/history',
component: history,
},
{
    path: '/find',
component: find,
},
{
    path: '/business',
component: business,
name:'business'
},
{
    path: '/feedback',
component: feedback,
},
];
var router = new VueRouter({
    // mode:'history',
	routes
});
new Vue({
    el: '#myapp',
	router
});