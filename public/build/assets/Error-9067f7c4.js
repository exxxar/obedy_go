import{_ as n}from"./PageTitle-5c0dafb3.js";import{B as _,j as r,o as p,d as i,a as t,b as l,F as d,C as u,D as m}from"./app-5d7ca779.js";const o=e=>(u("data-v-b93c4513"),e=e(),m(),e),b={class:"row justify-content-center w-100 absolute-center"},g=o(()=>t("span",{class:"errorPage__templeweed-container"},[t("img",{src:"/images/tembleweed.png",class:"errorPage__tembleweed"})],-1)),v=o(()=>t("div",{class:"errorPage__terrain"},null,-1)),f={__name:"Error",props:{status:Number},setup(e){const s=e,a=r(()=>({503:"503: Ресурс недоступен",500:"500: Произошла ошибка, свяжитесь с серверным специалистом",404:"404: Страница не найдена",403:"403: Запрещено"})[s.status]),c=r(()=>({503:"Извините, мы проводим кое-какие ремонтные работы. Пожалуйста, зайдите еще раз в ближайшее время.",500:"Упс, что-то пошло не так на наших серверах.",404:"Извините, страница, которую вы ищете, не была найдена",403:"Извините, вам запрещен доступ к этой странице."})[s.status]);return(h,w)=>(p(),i(d,null,[t("div",b,[l(n,{title:a.value,error:!0,description:c.value},null,8,["title","description"])]),g,v],64))}},E=_(f,[["__scopeId","data-v-b93c4513"]]);export{E as default};