import{_ as u}from"./AuthenticatedLayout-3_nkEi-e.js";import{o as s,c as h,w as m,b as e,d as l,t as o,f as a,h as c,F as i,r as d}from"./app--gSEcGom.js";import"./ApplicationLogo-G7dBEAp6.js";import"./_plugin-vue_export-helper-x3n3nnut.js";const f={class:"container mx-auto my-10"},g={class:"max-w-4xl mx-auto bg-white shadow-lg rounded-lg overflow-hidden"},y={class:"bg-gradient-to-l from-red-600 to-purple-500 h-32 relative"},x={class:"absolute bottom-0 left-1/2 transform -translate-x-1/2 -mb-10"},b=["src"],_={key:1,class:"w-20 h-20 rounded-full bg-gray-400 flex items-center justify-center text-white font-bold border-4 border-white"},k={class:"mt-12 px-6 pb-6 text-center"},v={class:"mt-4 text-2xl font-semibold text-gray-800 flex items-center justify-center"},w={key:0,class:"badge badge-accent ml-2"},j={key:0,class:"mt-4 text-gray-500"},p={class:"px-6 py-4"},B=e("h3",{class:"text-lg font-medium text-gray-700"},"User Information",-1),C=e("strong",null,"Email:",-1),F=e("strong",null,"Role:",-1),N=e("h3",{class:"text-lg font-medium text-gray-700 mt-6"},"Freelancer Details",-1),O=e("strong",null,"Specialization:",-1),V=e("strong",null,"Country:",-1),A=e("strong",null,"Hourly Rate:",-1),P=e("strong",null,"Bio:",-1),S=e("p",null,[e("strong",null,"Skills:")],-1),U={key:0},z={key:1},D=["href"],E={key:0,class:"px-6 py-4"},R=e("h3",{class:"text-lg font-medium text-gray-700"},"Projects",-1),T={class:"mt-4 space-y-4"},$={class:"text-blue-600 font-semibold"},H={class:"text-gray-600 text-sm mt-2"},I={key:1,class:"text-gray-500 px-6 py-4"},Q={__name:"UserProfile",props:{freelancer:Object,user:Object,projects:Array,skills:Object,avatar:Object},setup(t){const n=t;return console.log(n.skills),console.log(n.projects),console.log(n.freelancer),console.log(n.user),console.log(n.avatar),(L,q)=>(s(),h(u,null,{default:m(()=>[e("div",f,[e("div",g,[e("div",y,[e("div",x,[t.user.avatar&&t.user.avatar.photo_url?(s(),l("img",{key:0,src:t.user.avatar.photo_url,alt:"Avatar",class:"w-20 h-20 rounded-full border-4 border-white"},null,8,b)):(s(),l("div",_,o(t.user.name.charAt(0).toUpperCase()),1))])]),e("div",k,[e("h2",v,[a(o(t.user.name)+" ",1),t.user.role==="freelancer"?(s(),l("span",w," Freelancer ")):c("",!0)]),t.user.description?(s(),l("p",j,o(t.user.description),1)):c("",!0)]),e("div",p,[B,e("p",null,[C,a(" "+o(t.user.email),1)]),e("p",null,[F,a(" "+o(t.user.role),1)]),t.user.role==="freelancer"?(s(),l(i,{key:0},[N,e("p",null,[O,a(" "+o(t.freelancer.specialization),1)]),e("p",null,[V,a(" "+o(t.freelancer.country),1)]),e("p",null,[A,a(" $"+o(t.freelancer.hourly_rate),1)]),e("p",null,[P,a(" "+o(t.freelancer.bio),1)]),S,t.freelancer.skills&&t.freelancer.skills.length?(s(),l("ul",U,[(s(!0),l(i,null,d(t.freelancer.skills,r=>(s(),l("li",{key:r.id||r},o(r.name||r),1))),128))])):(s(),l("p",z,"No skills available")),t.freelancer.portfolio?(s(),l("a",{key:2,href:t.freelancer.portfolio,class:"text-blue-500",target:"_blank"}," View Portfolio ",8,D)):c("",!0)],64)):c("",!0)]),t.projects&&t.projects.length>0?(s(),l("div",E,[R,e("ul",T,[(s(!0),l(i,null,d(t.projects,r=>(s(),l("li",{key:r.id,class:"bg-gray-100 p-4 rounded-lg shadow-sm hover:shadow-md transition"},[e("h4",$,o(r.title),1),e("p",H,o(r.description),1)]))),128))])])):(s(),l("div",I," This user has not participated in any projects yet. "))])])]),_:1}))}};export{Q as default};
