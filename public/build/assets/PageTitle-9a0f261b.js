import{s as i,E as d,o as s,d as a,l as t,y as m,p as l,t as n,n as u,a as _}from"./app-88729ecd.js";const p={key:0,class:"col-8 px-3"},f=_("a",{href:"#",class:"text-danger font-weight-bold","data-bs-toggle":"modal","data-bs-target":"#menu"},"ТУТ",-1),h={key:1,class:"mt-2"},x={key:2,class:"mt-2"},b={__name:"PageTitle",props:{title:{type:String,default:null}},setup(o){const{foodParts:r,part:e}=i(d());return(g,y)=>(s(),a("div",{class:u(["w-100 d-flex flex-column align-items-center text-uppercase text-white text-center mt-4",t(e)===0?"mb-5 mt-5":""])},[t(e)!==null?(s(),a("h6",p,[m("Полное меню на неделю можно глянуть "),f])):l("",!0),t(e)!==null&&t(e)!==0?(s(),a("h1",h,n(t(r).find(c=>c.partId===t(e)).title),1)):l("",!0),t(e)===null&&o.title!==null?(s(),a("h1",x,n(o.title),1)):l("",!0)],2))}};export{b as _};
