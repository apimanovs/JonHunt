import{_ as p}from"./AuthenticatedLayout-3_nkEi-e.js";import{Q as v,e as i,o as e,c as w,w as y,b as s,f as d,t as a,d as o,F as h,r as _}from"./app--gSEcGom.js";import"./ApplicationLogo-G7dBEAp6.js";import"./_plugin-vue_export-helper-x3n3nnut.js";const b={class:"container mx-auto mt-6"},k=s("h1",{class:"text-2xl font-bold mb-4"},"Search Results",-1),A={class:"mb-4"},$={key:0,class:"mb-10"},U=s("h2",{class:"text-xl font-semibold mb-4"},"Users",-1),B={class:"flex flex-wrap -mx-2 sm:-mx-3"},C={class:"bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300 flex items-center p-4"},N=["src"],D={key:1,class:"w-16 h-16 bg-gray-400 text-white rounded-full flex items-center justify-center text-xl font-bold mr-4"},S=["href"],P={key:1,class:"text-gray-500"},q={key:2,class:"mb-10"},F=s("h2",{class:"text-xl font-semibold mb-4"},"Projects",-1),R={class:"flex flex-wrap -mx-2 sm:-mx-3"},T={class:"bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300"},V={class:"p-4"},E=["href"],J={class:"creator-info flex items-center mt-2"},L=["src"],Q={key:1,class:"w-8 h-8 bg-gray-400 text-white rounded-full flex items-center justify-center text-sm font-bold mr-2"},z=["href"],G={class:"text-gray-600 text-sm mt-2"},H=s("strong",null,"Budget:",-1),I={class:"text-gray-600 text-sm"},K=s("strong",null,"Completion Date:",-1),M={key:3,class:"text-gray-500"},O={key:4,class:"mb-10"},W=s("h2",{class:"text-xl font-semibold mb-4"},"Job Ads",-1),X={class:"flex flex-wrap -mx-2 sm:-mx-3"},Y={class:"bg-white shadow-md rounded-lg overflow-hidden hover:shadow-lg transition-shadow duration-300"},Z={class:"p-4"},j=["href"],tt={class:"creator-info flex items-center mt-2"},st=["src"],et={key:1,class:"w-8 h-8 bg-gray-400 text-white rounded-full flex items-center justify-center text-sm font-bold mr-2"},ot=["href"],at={class:"text-gray-600 text-sm mt-2"},rt=s("strong",null,"Description:",-1),lt={class:"text-gray-600 text-sm"},nt=s("strong",null,"Salary:",-1),ct={key:5,class:"text-gray-500"},ft={__name:"SearchPage",setup(dt){const r=v(),g=i(()=>r.props.query),m=i(()=>r.props.users||[]),u=i(()=>r.props.projects||[]),x=i(()=>r.props.jobAds||[]);return console.log(r.props),(it,ht)=>(e(),w(p,null,{default:y(()=>[s("div",b,[k,s("p",A,[d("Results for: "),s("strong",null,'"'+a(g.value)+'"',1)]),m.value.length?(e(),o("div",$,[U,s("div",B,[(e(!0),o(h,null,_(m.value,t=>(e(),o("div",{key:t.id,class:"w-full sm:w-1/2 md:w-1/3 lg:w-1/4 px-2 sm:px-3 mb-6"},[s("div",C,[t.avatar?(e(),o("img",{key:0,src:t.avatar.photo_url,alt:"User Avatar",class:"w-16 h-16 rounded-full mr-4"},null,8,N)):(e(),o("div",D,a(t.name.charAt(0).toUpperCase()),1)),s("a",{href:`/user/${t.username}`,class:"text-gray-800 font-semibold hover:underline"},a(t.name),9,S)])]))),128))])])):(e(),o("p",P,"No users found.")),u.value.length?(e(),o("div",q,[F,s("div",R,[(e(!0),o(h,null,_(u.value,t=>{var l,n,c,f;return e(),o("div",{key:t.id,class:"w-full sm:w-1/2 md:w-1/3 lg:w-1/4 px-2 sm:px-3 mb-6"},[s("div",T,[s("div",V,[s("a",{href:`/projects/${t.id}`,class:"text-lg font-semibold text-gray-800 hover:underline block truncate"},a(t.title),9,E),s("div",J,[(l=t.creator)!=null&&l.avatar?(e(),o("img",{key:0,src:t.creator.avatar.photo_url,alt:"Avatar",class:"w-8 h-8 rounded-full mr-2"},null,8,L)):(e(),o("span",Q,a((n=t.creator)==null?void 0:n.name.charAt(0).toUpperCase()),1)),s("a",{href:`/user/${(c=t.creator)==null?void 0:c.username}`,class:"text-gray-700 font-medium hover:text-blue-500"},a(((f=t.creator)==null?void 0:f.name)||"Unknown"),9,z)]),s("p",G,[H,d(" $"+a(t.budget),1)]),s("p",I,[K,d(" "+a(t.completion_date),1)])])])])}),128))])])):(e(),o("p",M,"No projects found.")),x.value.length?(e(),o("div",O,[W,s("div",X,[(e(!0),o(h,null,_(x.value,t=>{var l,n,c;return e(),o("div",{key:t.id,class:"w-full sm:w-1/2 md:w-1/3 lg:w-1/4 px-2 sm:px-3 mb-6"},[s("div",Y,[s("div",Z,[s("a",{href:`/gigs/${t.id}`,class:"text-lg font-semibold text-gray-800 hover:underline block truncate"},a(t.Title),9,j),s("div",tt,[t.creator&&t.creator.avatar?(e(),o("img",{key:0,src:t.creator.avatar.photo_url,alt:"Avatar",class:"w-8 h-8 rounded-full mr-2"},null,8,st)):(e(),o("span",et,a(((n=(l=t.creator)==null?void 0:l.name)==null?void 0:n.charAt(0).toUpperCase())||"?"),1)),s("a",{href:t.creator?`/user/${t.creator.username}`:"#",class:"text-gray-700 font-medium hover:text-blue-500"},a(((c=t.creator)==null?void 0:c.name)||"Unknown"),9,ot)]),s("p",at,[rt,d(" "+a(t.Description),1)]),s("p",lt,[nt,d(" $"+a(t.Price),1)])])])])}),128))])])):(e(),o("p",ct,"No job ads found."))])]),_:1}))}};export{ft as default};
