(()=>{"use strict";let t,e,r,n,o;var s,a,l={},c={};function d(t){var e=c[t];if(void 0!==e)return e.exports;var r=c[t]={exports:{}};return l[t](r,r.exports,d),r.exports}d.rv=()=>"1.7.4",d.ruid="bundler=rspack@1.7.4";let h=t=>({_parent:t,_effects:[],_subscopes:[],_details:{...t?._details},_run(t){let e=u;u=this;try{return t()}finally{u=e}},_cleanup(){for(let t=this._subscopes.length-1;t>=0;t--)this._subscopes[t]._cleanup();this._subscopes=[];for(let t=this._effects.length-1;t>=0;t--){let e=this._effects[t];e._clean?.(),e._run=()=>{},e._deps.forEach(t=>t._effects.delete(e)),e._deps.clear()}this._effects=[]}}),u=h(),f=!1,p=()=>u,g=(r,n)=>{let o=()=>(!f&&t&&(t._deps.add(o),o._effects.add(t)),o.peek());o._effects=new Set,o.peek=()=>r;let s=(t,a)=>{let l={...n,...a};if(l.equals??=(t,e)=>t===e,e){let n="function"==typeof t?t(o.peek()):t;(l?.force||!l.equals(n,o.peek()))&&(l?.force?r=n:e._setters.push(()=>r=n),l?.silent||o._effects.forEach(t=>{t._pure?e._pureEffects.add(t):e._effects.add(t)}))}else m(()=>s(t,l))};return[o,s]},m=t=>{if(e)return t();e={_setters:[],_effects:new Set,_pureEffects:new Set};try{let e=t();return v(),e}finally{e=void 0}},v=()=>{for(;e&&e._setters.length+e._effects.size+e._pureEffects.size>0;){e._effects.forEach(t=>t._clean?.()),e._setters.forEach(t=>t()),e._setters=[];let t=e._pureEffects.values().next().value??e._effects.values().next().value;t&&(t._run(),e._pureEffects.delete(t),e._effects.delete(t))}},b=!1,_=(e,r)=>{let n=!!r,o={_scope:u,_pure:b,_deps:new Set,_run(){let o=t,s=f;t=this;try{this._deps.forEach(t=>t._effects.delete(this)),this._deps.clear(),r&&(f=!1,r.forEach(t=>t())),f=n,this._clean?.();let t=this._scope._run(()=>m(e));this._clean=t?()=>{this._scope._run(()=>m(t)),this._clean=null}:null}finally{t=o,f=s}}};u._effects.push(o),o._run(),o._deps.size||o._clean||u._effects.pop()},w=(t,e)=>{let[r,n]=g(void 0,e),o=!0;b=!0;try{_(()=>{n(t,o?{force:!0}:{}),o=!1})}finally{b=!1}return r},x=(t,r)=>{let n=e;e=void 0;let o=u,s=h(o);Object.assign(s._details,r?.details);try{return o._subscopes.push(s),[s._run(t),()=>{let t=o._subscopes.indexOf(s);t>=0&&o._subscopes.splice(t,1),s._cleanup()}]}finally{e=n}},y={upgrade:t=>()=>y.get(t),get:t=>"function"==typeof t?t():t,peek(t){let e=f;f=!0;try{return this.get(t)}finally{f=e}}},k=(t={})=>({_ifConditions:[],_node(t){return this._nodes?.next().value??t()},...t}),C=()=>{let t=p();return t._details._renderer??=k()},E=(t,e)=>{let[r,n]=x(e,{details:{_renderer:k({...C(),...t})}});return _(()=>n),r},M=t=>(t[0]??"").toLowerCase()+t.slice(1).replace(/[A-Z]/g,t=>`-${t.toLowerCase()}`),z=t=>t.startsWith("on:")?t.slice(3):M(t.slice(2)),S=Symbol("Context"),I=t=>!!t?.[S],L=(t,e,r)=>{e.addEventListener(t[S],t=>{let e=y.get(r);void 0!==e&&(t.stopPropagation(),t.detail(e))})};(s=a||(a={})).forEach=(t,e)=>t.forEach(t=>Array.isArray(t)?s.forEach(t,e):e(t)),s.last=(t,e=t.length-1)=>{if(t.length)for(let r=e;r>=0;r--){let e=t[r];if(!Array.isArray(e))return e;let n=s.last(e);if(n)return n}};let A=t=>({build(){let e=t();return e.build?.()??e}}),H=(t,e)=>({_tag:"p",_defaultOrContext:t,...e}),j=(t=CustomEvent)=>({_tag:"e",_event:t}),P=Symbol("Component"),T=(t,e)=>{r?r.push([t,e]):_(t,e)},N=(t,e={},n={})=>{let o=[],s=new Map;for(let t in e){let r=e[t];if("p"==r._tag&&r.attribute){"function"==typeof r.attribute&&(r.attribute={transform:r.attribute});let e=r.attribute={name:M(t),static:!1,transform:t=>t,...r.attribute};s.set(e.name,{name:t,meta:r}),e.static||o.push(e.name)}}n.shadow??={mode:"open"};class l extends HTMLElement{static [P]={_tagName:t};static observedAttributes=o;props={};events={};[P]={};constructor(){for(const t in super(),e){const r=e[t];if("p"==r._tag){const e=I(r._defaultOrContext)?r._defaultOrContext:null,[n,o]=g(e?void 0:r._defaultOrContext);this.props[t]=n,e&&L(e,this,n),Object.defineProperty(this,t,{get:n.peek,set:t=>o(()=>t,{force:!0})})}else if("e"==r._tag&&t.startsWith("on")){const e=z(t);this.events[t]=t=>this.dispatchEvent(new r._event(e,t))}}}connectedCallback(){let t=n.shadow?this.shadowRoot??this.attachShadow(n.shadow):this;this[P]._destroy=x(()=>E({_svg:!1,_component:this,_nodes:t.childNodes.values()},()=>{this[P]._scope=p();let e=r;r=[];try{a.forEach(this.render().build(),e=>{t.append(e)}),r.forEach(([t,e])=>_(t,e))}finally{r=e}}))[1]}disconnectedCallback(){this[P]._destroy?.()}attributeChangedCallback(t,e,r){let n=s.get(t);n&&(this[n.name]=null!=r?n.meta.attribute.transform.call(this,r):I(n.meta._defaultOrContext)?void 0:n.meta._defaultOrContext)}}return l},q=(...t)=>{let[e,r]="string"==typeof t[0]?[t[0],t.slice(1)]:["",t];for(let t of r)customElements.define(e+t[P]._tagName,t)},O=/acit|ex(?:s|g|n|p|$)|rph|grid|ows|mnc|ntw|ine[ch]|zoo|^ord|itera/i,B=(t,e,r)=>{"-"==e[0]?t.style.setProperty(e,`${r}`):t.style[e]=null==r?"":"number"!=typeof r||O.test(e)?`${r}`:`${r}px`},$=(t,e,r,n)=>{let o=null==r||!1===r&&!e.includes("-");if(e.startsWith("prop:"))t[e.slice(5)]=r;else if(e.startsWith("attr:"))e=e.slice(5),o?t.removeAttribute(e):t.setAttribute(e,r);else if(!["innerHTML","outerHTML"].includes(e)){if(!["tabIndex","role",...n?["width","height","href","list","form","download","rowSpan","colSpan"]:[]].includes(e)&&e in t)try{t[e]=r;return}catch(t){}"function"==typeof r||(o?t.removeAttribute(e):t.setAttribute(e,r))}},D=({text:t,marker:e})=>A(()=>{let r=C(),n=e&&r._node(()=>document.createComment("")),o=r._node(()=>document.createTextNode(""));return _(()=>{let e=""+(y.get(t)??"");o.textContent!=e&&(o.textContent=e)}),n?[n,o]:[o]}),W=({children:t})=>A(()=>Array.isArray(t)?t.flatMap(t=>W({children:t}).build()):null==t?[]:["object"==typeof t?t.build():D({text:t}).build()]),R=(t,e,r,n)=>{let{ref:o,style:s,children:l,dangerouslySetInnerHTML:c,...d}=r;for(let e in s??{}){let r=s[e];_(()=>{B(t,e,y.get(r))})}for(let e in d){let r=d[e];if(e.startsWith("on")){let n=p(),o=t=>{n._run(()=>m(()=>r(t)))},s=z(e);_(()=>(t.addEventListener(s,o),()=>t.removeEventListener(s,o)))}else _(()=>{$(t,e,y.get(r),n)})}return c&&_(()=>{let e=y.get(c).__html;t.innerHTML!=e&&(t.innerHTML=e)}),o&&_(()=>(o.set(t),()=>o.set(void 0))),null!=r.children&&a.forEach(E({_svg:e,_nodes:t.childNodes.values()},()=>W({children:r.children}).build()),e=>t.append(e)),t},V=(t,e={},r)=>(null!=r&&(e.children=r),t?.[P]?A(()=>{let r=C()._node(()=>new t);return customElements.upgrade(r),R(r,!1,e),[r]}):"function"==typeof t?A(()=>t(e)):((t,e={})=>A(()=>{let r=C(),n="svg"==t||!!r._svg;return[R(r._node(()=>n?document.createElementNS("http://www.w3.org/2000/svg",t):document.createElement(t)),n,e,!0)]}))(t,e));new Proxy(V,{get:(t,e)=>(r,n)=>t(e,r,n)});let Z=(t,e,r)=>(e&&null!=r&&(e.key=r),V(t,e)),U=t=>(C()._ifConditions=[],F({condition:t.condition,children:t.children})),F=t=>{let e=C(),r=e._ifConditions,n=y.upgrade(t.condition),o=w(()=>r.every(t=>!t())&&n());return e._ifConditions=[...r,n],A(()=>E({_ifConditions:[]},()=>{let r=e._node(()=>document.createComment("")),n=[r,[]],s=w(()=>o()?W({children:t.children}):null),l=[];return _(()=>{a.forEach(l,t=>t.parentNode?.removeChild(t)),n[1]=[];let[,t]=x(()=>{l=s()?.build()??[],n[1]=l;let t=r;a.forEach(l,e=>{t.parentNode?.insertBefore(e,t.nextSibling),t=e})});return t},[s]),n}))},G=({children:t})=>F({condition:!0,children:t}),X=Symbol("styleSheetRegistry"),Y=new Map,J=t=>{let e=t.children;if("function"==typeof e){let r=V("style",{},D({text:e,marker:!1}));return t.light?(({mount:t,children:e})=>A(()=>E({_nodes:void 0},()=>{let r=W({children:e}).build();return _(()=>(a.forEach(r,e=>t.appendChild(e)),()=>{a.forEach(r,t=>t.parentNode?.removeChild(t))}),[]),[]})))({mount:document.head,children:r}):r}if(e){let r=C(),n=t.light?document:r._component?.shadowRoot??document,o=((t,e,r)=>{if(!Y.has(e)){let t=new CSSStyleSheet;t.replaceSync(e),Y.set(e,{_sheet:t,_refs:0})}let n=Y.get(e);n._refs++,t.has(e)||t.set(e,{_sheet:n._sheet,_refs:0});let o=t.get(e);return o._refs++,_(()=>()=>{--o._refs||(t.delete(e),r()),--n._refs||Y.delete(e)}),o._sheet})(n[X]??=new Map,e,()=>{n.adoptedStyleSheets=n.adoptedStyleSheets.filter(t=>t!=o)});n.adoptedStyleSheets.push(o)}return W({})},K=(t,...e)=>{let r=()=>t.reduce((t,r,n)=>t+r+(y.get(e[n])??""),"");return e.some(t=>"function"==typeof t)?r:r()};class Q extends N("global-style"){render(){return Z(J,{light:!0,children:K`
        * {
          margin: 0;
          padding: 0;
          box-sizing: border-box;
        }

        html {
          scroll-behavior: smooth;
        }

        body {
          --standard-padding: 1rem;
          --main-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg"/>');
          --background-color: rgb(25, 25, 25);
          --color: rgb(238, 238, 238);
          --link-color: rgb(160, 149, 240);
          --heading-font-size: 1.7rem;
          --heading-size: calc(2.2 * var(--heading-font-size));
          --max-width: 35rem;
          background-color: var(--background-color);
          color: var(--color);
          color-scheme: dark;
          font-family: Inter, sans-serif;
          line-height: 1.5;
          transition:
            background-color 1s,
            color 1s;
        }

        a {
          color: var(--link-color);
          text-decoration: none;
          transition: color 1s;
        }

        a:hover {
          text-decoration: underline;
        }

        #root {
          position: relative;
          margin: 0 auto;
          padding: var(--heading-size) 0 var(--heading-size);
          max-width: var(--max-width);
        }

        /* Background image blur */
        #root::before {
          content: "";
          position: fixed;
          top: -10%;
          left: -10%;
          width: 110%;
          height: 110%;
          background-image: var(--main-image);
          background-position: center;
          filter: blur(3rem);
          opacity: 0.1;
          z-index: -1;
          transition: background-image 1s;
        }
      `})}}q(Q);let tt=()=>Z("svg",{width:"100%",height:"100%",viewBox:"0 0 24 24",fill:"none",children:Z("path",{d:"M19 12H5M5 12L12 19M5 12L12 5",stroke:"currentColor","stroke-width":"2","stroke-linecap":"round","stroke-linejoin":"round"})}),te=()=>Z("svg",{width:"100%",height:"100%",viewBox:"0 0 24 24",fill:"none",children:[Z("path",{d:"M12 22C13 17 20 16.4183 20 10C20 5.58172 16.4183 2 12 2C7.58172 2 4 5.58172 4 10C4 16.4183 11 17 12 22Z",stroke:"currentColor","stroke-width":"2","stroke-linecap":"round","stroke-linejoin":"round"}),Z("path",{d:"M12 13C13.6569 13 15 11.6569 15 10C15 8.34315 13.6569 7 12 7C10.3431 7 9 8.34315 9 10C9 11.6569 10.3431 13 12 13Z",stroke:"currentColor","stroke-width":"2","stroke-linecap":"round","stroke-linejoin":"round"})]}),tr=()=>Z("svg",{width:"100%",height:"100%",viewBox:"0 0 24 24",fill:"none",children:Z("path",{d:"M21 10H3M16 2V6M8 2V6M7.8 22H16.2C17.8802 22 18.7202 22 19.362 21.673C19.9265 21.3854 20.3854 20.9265 20.673 20.362C21 19.7202 21 18.8802 21 17.2V8.8C21 7.11984 21 6.27976 20.673 5.63803C20.3854 5.07354 19.9265 4.6146 19.362 4.32698C18.7202 4 17.8802 4 16.2 4H7.8C6.11984 4 5.27976 4 4.63803 4.32698C4.07354 4.6146 3.6146 5.07354 3.32698 5.63803C3 6.27976 3 7.11984 3 8.8V17.2C3 18.8802 3 19.7202 3.32698 20.362C3.6146 20.9265 4.07354 21.3854 4.63803 21.673C5.27976 22 6.11984 22 7.8 22Z",stroke:"currentColor","stroke-width":"2","stroke-linecap":"round","stroke-linejoin":"round"})}),tn=()=>Z("svg",{width:"100%",height:"100%",viewBox:"0 0 24 24",fill:"none",children:Z("path",{d:"M15 18L9 12L15 6",stroke:"currentColor","stroke-width":"2","stroke-linecap":"round","stroke-linejoin":"round"})}),to=()=>Z("svg",{width:"100%",height:"100%",viewBox:"0 0 24 24",fill:"none",children:Z("path",{d:"M9 18L15 12L9 6",stroke:"currentColor","stroke-width":"2","stroke-linecap":"round","stroke-linejoin":"round"})});class ti extends N("header-bar",{backHref:H(null,{attribute:String}),onBackClick:j(MouseEvent)}){render(){let[t,e]=g(!1);return T(()=>{function t(){e(document.scrollingElement.scrollTop>0)}return document.addEventListener("scroll",t),()=>{document.removeEventListener("scroll",t)}}),Z(W,{children:[Z("div",{class:()=>"wrapper "+(null==this.props.backHref()?"noback ":"")+(t()?"opaque ":""),children:[Z("a",{part:"back",href:()=>this.props.backHref()??"#",title:"Back",onclick:this.events.onBackClick,children:Z(tt,{})}),Z("h1",{part:"heading",children:Z("slot",{children:"Spirit"})})]}),Z(J,{children:K`
          :host {
            background-color: ${()=>t()?"var(--background-color)":"transparent"};
          }
        `}),Z(J,{children:K`
          * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
          }

          :host {
            position: fixed;
            top: 0;
            right: 0;
            width: 100%;
            height: var(--heading-size);
            line-height: 2.2;
            transition:
              background-color 1s,
              box-shadow 1s;
            z-index: 100;
          }

          .wrapper {
            display: flex;
            margin: 0 auto;
            width: 100%;
            height: var(--heading-size);
            max-width: var(--max-width);
          }

          [part="back"] {
            padding: 1rem var(--standard-padding);
            color: var(--link-color);
            text-decoration: none;
            transition: color 1s;
          }

          .noback [part="back"] {
            display: none;
          }

          [part="heading"] {
            flex: 1;
            margin-right: var(--standard-padding);
            font-size: var(--heading-font-size);
            font-weight: normal;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
          }

          .noback [part="heading"] {
            margin-left: var(--standard-padding);
          }
        `})]})}}q(ti);class ts extends N("journey-list"){render(){return Z(W,{children:[Z("div",{class:"wrapper",children:Z("slot",{name:"title"})}),Z("div",{part:"list",children:Z("slot",{})}),Z(J,{children:K`
          * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
          }

          .wrapper {
            padding: 1rem var(--standard-padding) 0.5rem;
          }

          ::slotted([slot="title"]) {
            font-weight: normal;
            font-size: 1rem;
          }

          [part="list"] {
            display: flex;
            flex-direction: column;
            padding-bottom: 1rem;
          }
        `})]})}}class ta extends N("journey-list-item",{href:H("#",{attribute:String})}){render(){return Z(W,{children:[Z("a",{href:this.props.href,children:[Z("slot",{name:"img"}),Z("span",{class:"shade"}),Z("span",{part:"text",children:Z("slot",{})})]}),Z(J,{children:K`
          :host {
            display: grid;
            height: 6rem;
            background-color: black;
            background-size: cover;
            background-position: center;
          }

          a {
            position: relative;
            text-decoration: none;
            color: white;
            overflow: hidden;
          }

          [part="text"] {
            position: absolute;
            bottom: 0.2rem;
            left: var(--standard-padding);
            font-weight: bold;
            text-shadow: 0 0 1rem black;
          }

          .shade {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 0, 0, 0.2);
          }

          ::slotted(img) {
            position: absolute;
            top: 50%;
            left: 50%;
            width: calc(100% + 4rem);
            transform: translate(-50%, -50%);
            transition: transform 0.5s;
          }

          a:hover ::slotted(img) {
            transform: translate(calc(-50% + 2rem), -50%);
          }
        `})]})}}q(ts,ta);class tl extends N("markdown-content"){render(){return Z(W,{children:[Z("div",{class:"wrapper",children:Z("slot",{})}),Z(J,{children:K`
          .wrapper {
            padding: 0 var(--standard-padding);
          }

          ::slotted(p) {
            margin: 0.5rem 0 !important;
          }
        `})]})}}q(tl);class tc extends N("photo-feed"){render(){return Z(W,{children:[Z("slot",{}),Z(J,{children:K`
          :host {
            display: flex;
            flex-direction: column;
          }
        `})]})}}class td extends N("photo-feed-item",{location:H("",{attribute:String}),locationHref:H("",{attribute:String}),date:H("",{attribute:String})}){render(){return Z(W,{children:[Z("slot",{name:"img"}),Z("p",{part:"meta",children:[Z(U,{condition:()=>""!=this.props.location(),children:Z("span",{part:"location",children:[Z(te,{}),Z(U,{condition:()=>""!=this.props.locationHref(),children:Z("a",{href:()=>this.props.locationHref(),target:"_blank",children:this.props.location})}),Z(G,{children:this.props.location})]})}),Z(U,{condition:()=>""!=this.props.date(),children:Z("span",{part:"date",children:[Z(tr,{}),Z(U,{condition:""!==this.id,children:Z("a",{href:"#"+this.id,title:"Permalink",children:this.props.date})}),Z(G,{children:this.props.date})]})})]}),Z(tl,{children:Z("slot",{})}),Z(J,{children:K`
          :host::before {
            content: " ";
            display: block;
            height: var(--heading-size);
            pointer-events: none;
          }

          :host(:first-child) {
            margin-top: calc(-1 * var(--heading-size) + 1rem) !important;
          }

          ::slotted([slot="img"]) {
            display: block;
            width: 100%;
          }

          [part="meta"] {
            display: flex;
            gap: 1rem;
            padding: 0 var(--standard-padding);
            margin-bottom: 1rem;
            color: var(--link-color);
            font-size: 0.8rem;
            transition: color 1s;
          }

          [part="meta"] > span {
            display: inline-flex;
            align-items: center;
          }

          [part="meta"] a {
            color: inherit;
            text-decoration: none;
          }

          [part="meta"] a:hover {
            text-decoration: underline;
          }

          [part="meta"] svg {
            height: 1.1em;
            width: 1.1em;
            margin-right: 0.5em;
          }
        `})]})}}class th extends N("photo-feed-imageset",{currentIndex:H(0,{attribute:Number}),onCurrentIndexChange:j()}){render(){let[t,e]=g(0),r=((t,e)=>{let[r,n]=g(void 0,void 0);return r.set=n,r})(),n=w(()=>this.props.currentIndex());return T(()=>{this.events.onCurrentIndexChange()},[n]),T(()=>{r()?.scrollTo({left:n()*this.clientWidth,behavior:"smooth"})}),Z(W,{children:[Z("div",{ref:r,part:"gallery",onscrollend:t=>{let e=t.currentTarget,r=Math.round(e.scrollLeft/e.clientWidth);r!==this.props.currentIndex()&&(this.currentIndex=r,this.events.onCurrentIndexChange())},children:Z("slot",{onslotchange:()=>{e(this.children.length)}})}),Z(U,{condition:()=>this.props.currentIndex()>0,children:Z("a",{class:"left",href:"#",onclick:t=>{t.preventDefault(),this.currentIndex>0&&this.currentIndex--},children:Z(tn,{})})}),Z(U,{condition:()=>this.props.currentIndex()<t()-1,children:Z("a",{class:"right",href:"#",onclick:e=>{e.preventDefault(),this.currentIndex<t()-1&&this.currentIndex++},children:Z(to,{})})}),Z(J,{children:K`
          :host {
            position: relative;
          }

          [part="gallery"] {
            display: flex;
            overflow: auto;
            scroll-behavior: smooth;
            scrollbar-width: none;
            scroll-snap-type: x mandatory;
            scroll-snap-stop: always;
          }

          ::slotted(img) {
            display: block;
            width: 100%;
            scroll-snap-align: start;
          }

          a.left,
          a.right {
            position: absolute;
            top: 50%;
            display: grid;
            border-radius: 50%;
            padding: 0.1rem;
            color: white;
            background-color: rgba(0, 0, 0, 0.5);
            transform: translateY(-50%);
          }

          a.left svg,
          a.right svg {
            height: 1.5rem;
          }

          a.left {
            left: 0.2rem;
          }

          a.right {
            right: 0.5rem;
          }
        `})]})}}q(tc,td,th);var tu=function(t,e){return t<e?-1:+(t>e)},tf=function(t){return t.reduce(function(t,e){return t+e},0)},tp=function(){function t(t){this.colors=t}var e=t.prototype;return e.palette=function(){return this.colors},e.map=function(t){return t},t}(),tg=function(){function t(t,e,r){return(t<<10)+(e<<5)+r}function e(t){var e=[],r=!1;function n(){e.sort(t),r=!0}return{push:function(t){e.push(t),r=!1},peek:function(t){return r||n(),void 0===t&&(t=e.length-1),e[t]},pop:function(){return r||n(),e.pop()},size:function(){return e.length},map:function(t){return e.map(t)},debug:function(){return r||n(),e}}}function r(t,e,r,n,o,s,a){this.r1=t,this.r2=e,this.g1=r,this.g2=n,this.b1=o,this.b2=s,this.histo=a}function n(){this.vboxes=new e(function(t,e){return tu(t.vbox.count()*t.vbox.volume(),e.vbox.count()*e.vbox.volume())})}return r.prototype={volume:function(t){return this._volume&&!t||(this._volume=(this.r2-this.r1+1)*(this.g2-this.g1+1)*(this.b2-this.b1+1)),this._volume},count:function(e){var r=this.histo;if(!this._count_set||e){var n,o,s,a=0;for(n=this.r1;n<=this.r2;n++)for(o=this.g1;o<=this.g2;o++)for(s=this.b1;s<=this.b2;s++)a+=r[t(n,o,s)]||0;this._count=a,this._count_set=!0}return this._count},copy:function(){return new r(this.r1,this.r2,this.g1,this.g2,this.b1,this.b2,this.histo)},avg:function(e){var r=this.histo;if(!this._avg||e){var n,o,s,a,l=0,c=0,d=0,h=0;if(this.r1===this.r2&&this.g1===this.g2&&this.b1===this.b2)this._avg=[this.r1<<3,this.g1<<3,this.b1<<3];else{for(o=this.r1;o<=this.r2;o++)for(s=this.g1;s<=this.g2;s++)for(a=this.b1;a<=this.b2;a++)l+=n=r[t(o,s,a)]||0,c+=n*(o+.5)*8,d+=n*(s+.5)*8,h+=n*(a+.5)*8;this._avg=l?[~~(c/l),~~(d/l),~~(h/l)]:[~~(8*(this.r1+this.r2+1)/2),~~(8*(this.g1+this.g2+1)/2),~~(8*(this.b1+this.b2+1)/2)]}}return this._avg},contains:function(t){var e=t[0]>>3;return gval=t[1]>>3,bval=t[2]>>3,e>=this.r1&&e<=this.r2&&gval>=this.g1&&gval<=this.g2&&bval>=this.b1&&bval<=this.b2}},n.prototype={push:function(t){this.vboxes.push({vbox:t,color:t.avg()})},palette:function(){return this.vboxes.map(function(t){return t.color})},size:function(){return this.vboxes.size()},map:function(t){for(var e=this.vboxes,r=0;r<e.size();r++)if(e.peek(r).vbox.contains(t))return e.peek(r).color;return this.nearest(t)},nearest:function(t){for(var e,r,n,o=this.vboxes,s=0;s<o.size();s++)((r=Math.sqrt(Math.pow(t[0]-o.peek(s).color[0],2)+Math.pow(t[1]-o.peek(s).color[1],2)+Math.pow(t[2]-o.peek(s).color[2],2)))<e||void 0===e)&&(e=r,n=o.peek(s).color);return n},forcebw:function(){var t=this.vboxes;t.sort(function(t,e){return tu(tf(t.color),tf(e.color))});var e=t[0].color;e[0]<5&&e[1]<5&&e[2]<5&&(t[0].color=[0,0,0]);var r=t.length-1,n=t[r].color;n[0]>251&&n[1]>251&&n[2]>251&&(t[r].color=[255,255,255])}},{quantize:function(o,s){if(!Number.isInteger(s)||s<1||s>256)throw Error("Invalid maximum color count. It must be an integer between 1 and 256.");if(!o.length||s<2||s>256||!o.length||s<2||s>256)return!1;for(var a,l,c,d,h,u,f,p,g,m,v,b=[],_=new Set,w=0;w<o.length;w++){var x=o[w],y=x.join(",");_.has(y)||(_.add(y),b.push(x))}if(b.length<=s)return new tp(b);var k=(l=Array(32768),o.forEach(function(e){l[a=t(e[0]>>3,e[1]>>3,e[2]>>3)]=(l[a]||0)+1}),l);k.forEach(function(){});var C=(u=1e6,f=0,p=1e6,g=0,m=1e6,v=0,o.forEach(function(t){(c=t[0]>>3)<u?u=c:c>f&&(f=c),(d=t[1]>>3)<p?p=d:d>g&&(g=d),(h=t[2]>>3)<m?m=h:h>v&&(v=h)}),new r(u,f,p,g,m,v,k)),E=new e(function(t,e){return tu(t.count(),e.count())});function M(e,r){for(var n,o=e.size(),s=0;s<1e3;){if(o>=r||s++>1e3)return;if((n=e.pop()).count()){var a=function(e,r){if(r.count()){var n=r.r2-r.r1+1,o=r.g2-r.g1+1,s=Math.max.apply(null,[n,o,r.b2-r.b1+1]);if(1==r.count())return[r.copy()];var a,l,c,d,h=0,u=[],f=[];if(s==n)for(a=r.r1;a<=r.r2;a++){for(d=0,l=r.g1;l<=r.g2;l++)for(c=r.b1;c<=r.b2;c++)d+=e[t(a,l,c)]||0;u[a]=h+=d}else if(s==o)for(a=r.g1;a<=r.g2;a++){for(d=0,l=r.r1;l<=r.r2;l++)for(c=r.b1;c<=r.b2;c++)d+=e[t(l,a,c)]||0;u[a]=h+=d}else for(a=r.b1;a<=r.b2;a++){for(d=0,l=r.r1;l<=r.r2;l++)for(c=r.g1;c<=r.g2;c++)d+=e[t(l,c,a)]||0;u[a]=h+=d}return u.forEach(function(t,e){f[e]=h-t}),function(t){var e,n,o,s,l,c=t+"1",d=t+"2",p=0;for(a=r[c];a<=r[d];a++)if(u[a]>h/2){for(o=r.copy(),s=r.copy(),l=(e=a-r[c])<=(n=r[d]-a)?Math.min(r[d]-1,~~(a+n/2)):Math.max(r[c],~~(a-1-e/2));!u[l];)l++;for(p=f[l];!p&&u[l-1];)p=f[--l];return o[d]=l,s[c]=o[d]+1,[o,s]}}(s==n?"r":s==o?"g":"b")}}(k,n),l=a[0],c=a[1];if(!l)return;e.push(l),c&&(e.push(c),o++)}else e.push(n),s++}}E.push(C),M(E,.75*s);for(var z=new e(function(t,e){return tu(t.count()*t.volume(),e.count()*e.volume())});E.size();)z.push(E.pop());M(z,s);for(var S=new n;z.size();)S.push(z.pop());return S}}}().quantize,tm=function(t){this.canvas=document.createElement("canvas"),this.context=this.canvas.getContext("2d"),this.width=this.canvas.width=t.naturalWidth,this.height=this.canvas.height=t.naturalHeight,this.context.drawImage(t,0,0,this.width,this.height)};tm.prototype.getImageData=function(){return this.context.getImageData(0,0,this.width,this.height)};var tv=function(){};tv.prototype.getColor=function(t,e){return void 0===e&&(e=10),this.getPalette(t,5,e)[0]},tv.prototype.getPalette=function(t,e,r){var n=function(t){var e=t.colorCount,r=t.quality;if(void 0!==e&&Number.isInteger(e)){if(1===e)throw Error("colorCount should be between 2 and 20. To get one color, call getColor() instead of getPalette()");e=Math.min(e=Math.max(e,2),20)}else e=10;return(void 0===r||!Number.isInteger(r)||r<1)&&(r=10),{colorCount:e,quality:r}}({colorCount:e,quality:r}),o=new tm(t),s=tg(function(t,e,r){for(var n,o,s,a,l,c=[],d=0;d<e;d+=r)o=t[0+(n=4*d)],s=t[n+1],a=t[n+2],(void 0===(l=t[n+3])||l>=125)&&(o>250&&s>250&&a>250||c.push([o,s,a]));return c}(o.getImageData().data,o.width*o.height,n.quality),n.colorCount);return s?s.palette():null},tv.prototype.getColorFromUrl=function(t,e,r){var n=this,o=document.createElement("img");o.addEventListener("load",function(){e(n.getPalette(o,5,r)[0],t)}),o.src=t},tv.prototype.getImageData=function(t,e){var r=new XMLHttpRequest;r.open("GET",t,!0),r.responseType="arraybuffer",r.onload=function(){if(200==this.status){var t=new Uint8Array(this.response);i=t.length;for(var r=Array(i),n=0;n<t.length;n++)r[n]=String.fromCharCode(t[n]);var o=r.join("");e("data:image/png;base64,"+window.btoa(o))}},r.send()},tv.prototype.getColorAsync=function(t,e,r){var n=this;this.getImageData(t,function(t){var o=document.createElement("img");o.addEventListener("load",function(){e(n.getPalette(o,5,r)[0],this)}),o.src=t})};let tb=new tv,t_=new Map;function tw([t,e,r]){return .2126*t+.7152*e+.0722*r}o=new IntersectionObserver(t=>{let e=t.find(t=>t.target instanceof HTMLImageElement&&t.intersectionRatio>=.6)?.target;null!=e&&(clearTimeout(n),n=setTimeout(()=>{let t=function(t){if(t_.has(t))return t_.get(t);let e=tb.getPalette(t,5);return null==e?null:(e.sort((t,e)=>tw(t)-tw(e)),t_.set(t,e),e)}(e);null!=t&&(document.body.style.setProperty("--main-image",`url("${e.getAttribute("src")}")`),document.body.style.setProperty("--background-color",`rgb(${t[0].map(t=>.8*t).join(", ")})`),document.body.style.setProperty("--color",`rgb(${t.at(-1).join(", ")})`),document.body.style.setProperty("--link-color",`rgb(${t.at(-2).join(", ")})`),n=void 0)},500))},{threshold:.6}),window.addEventListener("DOMContentLoaded",()=>{for(let t of document.querySelectorAll("img"))o.observe(t)})})();
//# sourceMappingURL=main.js.map