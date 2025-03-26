import{Q as x,o as t,c as g,w as r,a as l,u as o,Z as f,b as e,d as a,F as h,r as u,t as d,f as b,i as m}from"./app--gSEcGom.js";import{_ as p}from"./AuthenticatedLayout-3_nkEi-e.js";import"./ApplicationLogo-G7dBEAp6.js";import"./_plugin-vue_export-helper-x3n3nnut.js";const y={class:"max-w-7xl mx-auto p-6 bg-white dark:bg-gray-800 shadow sm:rounded-lg mt-10"},v=e("h1",{class:"text-2xl font-bold mb-6 mt"},"My Orders",-1),w={class:"mb-8"},k=e("h2",{class:"text-xl font-semibold mb-2"},"Orders as Client",-1),j={key:0,class:"text-gray-500"},F={key:1,class:"space-y-4"},C={class:"flex justify-between items-center"},V={class:"text-lg font-bold"},A={class:"text-sm text-gray-600"},B={class:"text-sm"},O=e("h2",{class:"text-xl font-semibold mb-2"},"Orders as Freelancer",-1),N={key:0,class:"text-gray-500"},S={key:1,class:"space-y-4"},T={class:"flex justify-between items-center"},J={class:"text-lg font-bold"},M={class:"text-sm text-gray-600"},Y={class:"text-sm"},$={__name:"Index",setup(D){const{props:n}=x(),i=n.ordersAsClient,c=n.ordersAsFreelancer;return(_,E)=>(t(),g(p,null,{default:r(()=>[l(o(f),{title:"My Orders"}),e("div",y,[v,e("div",w,[k,o(i).length?(t(),a("div",F,[(t(!0),a(h,null,u(o(i),s=>(t(),a("div",{key:s.id,class:"border p-4 rounded-md shadow-sm bg-gray-50 dark:bg-gray-700"},[e("div",C,[e("div",null,[e("div",V," Job: "+d(s.job_application.job_ad.Title),1),e("div",A," Freelancer: "+d(s.freelancer.name),1),e("div",B," Status: "+d(s.status),1)]),l(o(m),{href:_.route("orders.show",s.id),class:"btn bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"},{default:r(()=>[b(" View ")]),_:2},1032,["href"])])]))),128))])):(t(),a("div",j," You have no orders as client. "))]),e("div",null,[O,o(c).length?(t(),a("div",S,[(t(!0),a(h,null,u(o(c),s=>(t(),a("div",{key:s.id,class:"border p-4 rounded-md shadow-sm bg-gray-50 dark:bg-gray-700"},[e("div",T,[e("div",null,[e("div",J," Job: "+d(s.job_application.job_ad.Title),1),e("div",M," Client: "+d(s.client.name),1),e("div",Y," Status: "+d(s.status),1)]),l(o(m),{href:_.route("orders.show",s.id),class:"btn bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"},{default:r(()=>[b(" View ")]),_:2},1032,["href"])])]))),128))])):(t(),a("div",N," You have no orders as freelancer. "))])])]),_:1}))}};export{$ as default};
