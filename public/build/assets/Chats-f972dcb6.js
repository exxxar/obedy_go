import{o as e,c as w,w as b,p as E,a as U,b as s,r as y,d as $,e as B,f as D,g as k,h as t,i as u,F as r,n as h,t as d,u as F,j as N,v as V,k as f,l as T,m as G,q as L}from"./app-8624114e.js";import{_ as O,s as m,a as z}from"./BaseLayout-b0f321c8.js";import{_ as A}from"./_plugin-vue_export-helper-c27b6911.js";const j=_=>(E("data-v-551bfca8"),_=_(),U(),_),R=j(()=>s("div",{class:"modal-header border-0"},[s("h5",{class:"modal-title"}),s("i",{class:"fa-solid fa-xmark text-white px-3 cursor-pointer fa-2xl","data-bs-dismiss":"modal"})],-1)),q=j(()=>s("div",{id:"carouselExampleIndicators",class:"carousel slide h-100"},[s("div",{class:"carousel-indicators"},[s("button",{type:"button","data-bs-target":"#carouselExampleIndicators","data-bs-slide-to":"0",class:"active","aria-current":"true","aria-label":"Slide 1"}),s("button",{type:"button","data-bs-target":"#carouselExampleIndicators","data-bs-slide-to":"1","aria-label":"Slide 2"}),s("button",{type:"button","data-bs-target":"#carouselExampleIndicators","data-bs-slide-to":"2","aria-label":"Slide 3"})]),s("div",{class:"carousel-inner h-100"},[s("div",{class:"carousel-item h-100 active"},[s("img",{src:"https://therichpost.com/wp-content/uploads/2020/06/avatar2.png",class:"d-block w-100 h-100 object-fit-contain",alt:"..."})]),s("div",{class:"carousel-item h-100"},[s("img",{src:"https://wallpaper-house.com/data/out/8/wallpaper2you_232438.jpg",class:"d-block w-100 h-100 object-fit-contain",alt:"..."})]),s("div",{class:"carousel-item h-100"},[s("img",{src:"https://therichpost.com/wp-content/uploads/2020/06/avatar2.png",class:"d-block w-100 h-100 object-fit-contain",alt:"..."})])]),s("button",{class:"carousel-control-prev",type:"button","data-bs-target":"#carouselExampleIndicators","data-bs-slide":"prev"},[s("i",{class:"fa-solid fa-chevron-left cursor-pointer fa-2xl","aria-hidden":"true"}),s("span",{class:"visually-hidden"},"Previous")]),s("button",{class:"carousel-control-next",type:"button","data-bs-target":"#carouselExampleIndicators","data-bs-slide":"next"},[s("i",{class:"fa-solid fa-chevron-right cursor-pointer fa-2xl","aria-hidden":"true"}),s("span",{class:"visually-hidden"},"Next")])],-1)),P={__name:"GalleryModal",setup(_){return(n,g)=>(e(),w(O,{id:"gallery",class_size:"modal-dialog-centered modal-fullscreen",class_content:"bg-transparent",header:!1,title:""},{header:b(()=>[R]),body:b(()=>[q]),_:1}))}},H=A(P,[["__scopeId","data-v-551bfca8"]]),J={class:"container p-0"},K={class:"w-100 row m-0 d-flex flex-wrap justify-content-center mb-5"},Q={class:"col-md-5 col-lg-4 col-12 chat"},W={class:"card mb-sm-3 mb-md-0 contacts_card"},X=s("div",{class:"card-header"},[s("div",{class:"input-group"},[s("input",{type:"text",placeholder:"Поиск...",autocomplete:"off",name:"chatSearch",id:"chatSearch",class:"form-control chat-search"}),s("span",{class:"input-group-text search_btn"},[s("i",{class:"fas fa-search"})])])],-1),Y={class:"card-body contacts_body"},Z={class:"contacts"},ss=["onClick"],es={class:"d-flex bd-highlight"},ts={class:"img_cont"},as=["src"],os={class:"user_info text-truncate"},ls={key:0,class:"text-truncate"},cs={key:1,class:"d-flex gap-2"},ns={class:"text-truncate"},is={key:0},ds={key:0},rs=s("i",{class:"fa-solid fa-check fa-lg"},null,-1),_s=[rs],us={key:1},hs=s("i",{class:"fa-solid fa-check-double fa-lg"},null,-1),fs=[hs],ps={key:1,class:"message-counter"},gs=s("div",{class:"card-footer"},null,-1),ms={class:"col-md-7 col-lg-8 col-12 chat"},bs={class:"card-header msg_head"},vs={class:"d-flex bd-highlight"},xs={class:"img_cont"},ys=["src"],ks={class:"user_info"},ws={class:"d-flex flex-column"},js={class:"d-flex justify-content-center mb-3"},Ms={class:"img_cont_msg"},Cs=["src"],Is={key:0,class:"d-flex flex-wrap gap-1 justify-content-center"},Ss={class:"col-auto"},Es=["src"],Us={key:1,class:"col-12 d-flex flex-wrap gap-2"},$s={class:"col-auto overflow-hidden file-link-container"},Bs=["href"],Ds=s("i",{class:"fa-solid fa-download"},null,-1),Fs={key:1},Ns={key:0},Vs=s("i",{class:"fa-solid fa-check fa-lg"},null,-1),Ts=[Vs],Gs={key:1},Ls=s("i",{class:"fa-solid fa-check-double fa-lg"},null,-1),Os=[Ls],zs={key:1,class:"text-center"},As={class:"card-footer"},Rs={class:"input-group"},qs=s("label",{class:"input-group-text attach_btn",for:"actual-btn"},[s("i",{class:"fas fa-paperclip"})],-1),Ps=["disabled"],Hs=s("i",{class:"fas fa-location-arrow"},null,-1),Js=[Hs],Ks={key:0,class:"d-flex flex-column fixed-height-min-max-125px"},Qs={class:"list-group overflow-x-hidden bg-white"},Ws={class:"list-group-item d-flex justify-content-between align-items-center bg-white px-4"},Xs=["id"],Ys=["onClick"],Zs=s("i",{class:"fa-solid fa-xmark fa-lg cursor-pointer"},null,-1),se=[Zs],ee={key:1,class:"text-center"},le={__name:"Chats",props:{chats:{type:Array,default:[]}},setup(_){const n=y({data:null}),g=$(null),v=()=>({message:"",files:[]}),i=y(v());B(()=>{route().params.chatId!==void 0&&x(route().params.chatId)});const M=D(()=>n.data===null?[]:new Set(n.data.messages.map(c=>c.date))),C=c=>n.data.messages.filter(a=>a.date===c),x=async c=>{await k.get(route("chat.messages",{id:c})).then(a=>{n.data=a.data,g.value=c}).catch(a=>{a.response.status===403&&m("Вы не имеете доступа к выбранному чату","error"),a.response.status===404&&m("Выбранный чат не найден","error")})},I=async()=>{let c=new FormData;c.append("chatId",g.value),c.append("message",i.message);for(let a=0;a<i.files.length;a++)c.append("files["+a+"]",i.files[a]);await k.post(route("send.message"),c,{headers:{"Content-Type":"multipart/form-data"}}).then(a=>{n.data.messages.push(a.data),Object.assign(i,v())}).catch(a=>{m("Произошла ошибка при отправке сообщения","error")})},S=async c=>{c.target.files.length>0&&Object.entries(c.target.files).forEach(([a,o])=>{i.files.push(o),G(()=>{document.getElementById("messageFile-"+a).setAttribute("href",window.URL.createObjectURL(o))})}),c.target.value=""};return(c,a)=>(e(),w(z,null,{content:b(()=>[s("div",J,[s("div",K,[s("div",Q,[s("div",W,[X,s("div",Y,[s("ul",Z,[(e(!0),t(r,null,u(_.chats,o=>(e(),t("li",{class:"active",onClick:l=>x(o.id)},[s("div",es,[s("div",ts,[s("img",{src:o.interlocutor.image,class:"rounded-circle object-fit-cover user_img"},null,8,as)]),s("div",os,[s("span",null,d(o.interlocutor.name),1),o.lastMessage===null?(e(),t("p",ls,"В чате пока нет сообщений")):(e(),t("div",cs,[s("p",ns,[o.lastMessage.isUserMessage?(e(),t("strong",is,"Вы: ")):f("",!0),L(" "+d(o.lastMessage.message===null?"Вложения":o.lastMessage.message),1)]),o.lastMessage.isUserMessage?(e(),t(r,{key:0},[o.lastMessage.isSeen?(e(),t("p",us,fs)):(e(),t("p",ds,_s))],64)):(e(),t("span",ps,"3"))]))])])],8,ss))),256))])]),gs])]),s("div",ms,[s("div",{class:h(["card overflow-auto",n.data!==null?"":"justify-content-center"])},[n.data!==null?(e(),t(r,{key:0},[s("div",bs,[s("div",vs,[s("div",xs,[s("img",{src:n.data.interlocutor.image,class:"rounded-circle object-fit-cover user_img"},null,8,ys)]),s("div",ks,[s("span",null,d(n.data.interlocutor.name),1),s("p",null,d(n.data.messageCount)+" сообщений",1)])])]),s("div",{class:h(["card-body msg_card_body",n.data.messages.length>0?"":"d-flex flex-column justify-content-center"])},[n.data.messages.length>0?(e(!0),t(r,{key:0},u(F(M),o=>(e(),t("div",ws,[s("div",js,d(o),1),(e(!0),t(r,null,u(C(o),l=>(e(),t("div",{class:h(["d-flex justify-content-start mb-4",l.isUserMessage?"flex-row-reverse":""])},[s("div",Ms,[s("img",{src:l.isUserMessage?n.data.user.image:n.data.interlocutor.image,class:"rounded-circle object-fit-cover user_img_msg"},null,8,Cs)]),s("div",{class:h(l.isUserMessage?"msg_container_send":"msg_container")},[l.files.length>0||l.images.length>0?(e(),t("div",{key:0,class:h(["file-container d-flex flex-wrap gap-2 justify-content-center",l.message!==null?"mb-2":""])},[l.images.length>0?(e(),t("div",Is,[(e(!0),t(r,null,u(l.images,p=>(e(),t("div",Ss,[s("img",{src:p,class:"img-fluid rounded image-container","data-bs-toggle":"modal","data-bs-target":"#gallery"},null,8,Es)]))),256))])):f("",!0),l.files.length>0?(e(),t("div",Us,[(e(!0),t(r,null,u(l.files,p=>(e(),t("div",$s,[s("a",{class:"d-flex gap-2 align-items-center text-break justify-content-between link",href:p.path,target:"_blank"},[s("p",null,d(p.name),1),Ds],8,Bs)]))),256))])):f("",!0)],2)):f("",!0),l.message!==null?(e(),t("p",Fs,d(l.message),1)):f("",!0),s("div",{class:h(["d-flex gap-2",l.isUserMessage?"msg_time_send":"msg_time"])},[s("span",null,d(l.time),1),l.isSeen?(e(),t("span",Gs,Os)):(e(),t("span",Ns,Ts))],2)],2)],2))),256))]))),256)):(e(),t("p",zs,"В чате пока нет сообщений"))],2),s("div",As,[s("div",Rs,[s("input",{type:"file",id:"actual-btn",onChange:a[0]||(a[0]=o=>S(o)),multiple:"",hidden:""},null,32),qs,N(s("textarea",{name:"message",class:"form-control type_msg","onUpdate:modelValue":a[1]||(a[1]=o=>i.message=o),placeholder:"Напишите ваше сообщение..."},null,512),[[V,i.message]]),s("button",{class:"input-group-text send_btn",onClick:I,disabled:i.message===null&&i.files.length===0},Js,8,Ps)])]),i.files.length>0?(e(),t("div",Ks,[s("ul",Qs,[(e(!0),t(r,null,u(i.files,(o,l)=>(e(),t("li",Ws,[s("a",{id:"messageFile-"+l,href:"#",target:"_blank"},d(o.name),9,Xs),s("span",{onClick:p=>i.files.splice(l,1)},se,8,Ys)]))),256))])])):f("",!0)],64)):(e(),t("p",ee,"Выберите кому хотели бы написать"))],2)])]),T(H)])]),_:1}))}};export{le as default};
