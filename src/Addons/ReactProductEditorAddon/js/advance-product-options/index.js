(()=>{"use strict";var e={20:(e,t,o)=>{var r=o(609),n=Symbol.for("react.element"),i=(Symbol.for("react.fragment"),Object.prototype.hasOwnProperty),s=r.__SECRET_INTERNALS_DO_NOT_USE_OR_YOU_WILL_BE_FIRED.ReactCurrentOwner,a={key:!0,ref:!0,__self:!0,__source:!0};function l(e,t,o){var r,l={},c=null,d=null;for(r in void 0!==o&&(c=""+o),void 0!==t.key&&(c=""+t.key),void 0!==t.ref&&(d=t.ref),t)i.call(t,r)&&!a.hasOwnProperty(r)&&(l[r]=t[r]);if(e&&e.defaultProps)for(r in t=e.defaultProps)void 0===l[r]&&(l[r]=t[r]);return{$$typeof:n,type:e,key:c,ref:d,props:l,_owner:s.current}}t.jsx=l,t.jsxs=l},848:(e,t,o)=>{e.exports=o(20)},609:e=>{e.exports=window.React}},t={};const o=window.wp.blocks,r=window.wc.blockTemplates,n=(window.wp.i18n,window.wp.components),i=window.wp.element,s=window.wp.coreData;function a(e,t){const[o,r]=(0,s.useEntityProp)("postType",t,"tiered_pricing_product_settings");return o.hasOwnProperty(e)?[o[e],t=>{let n=Object.assign({},o);n[e]=t,r(n)}]:[null,()=>{}]}var l=function o(r){var n=t[r];if(void 0!==n)return n.exports;var i=t[r]={exports:{}};return e[r](i,i.exports,o),i.exports}(848);function c({context:e,attributes:t}){const o=e.postType||"product",[r,s]=a("layout",o),c=(0,i.useMemo)((()=>{let e=[];return e.push({label:"Default",value:"default"}),void 0!==t.availableLayouts&&Object.keys(t.availableLayouts).forEach(((o,r)=>{e.push({label:t.availableLayouts[o],value:o})})),e}),[]);return(0,l.jsx)("div",{style:{width:"100%"},children:(0,l.jsx)(n.SelectControl,{label:"Layout",value:r||"default",options:c,onChange:e=>s(e)})})}function d({context:e}){const t=e.postType||"product",[o,r]=a("base_unit_name",t);return(0,l.jsx)("div",{style:{width:"100%",display:"flex"},children:(0,l.jsx)("div",{className:"tiered-pricing-product-editor-row",children:(0,l.jsxs)("div",{className:"tiered-pricing-product-editor-columns",style:{gap:"20px"},children:[(0,l.jsx)("div",{className:"tiered-pricing-product-editor-column",children:(0,l.jsx)(n.BaseControl,{id:"tiered_pricing_advanced_options_base_unit_name_singular",children:(0,l.jsx)(n.__experimentalInputControl,{value:o.singular||"",placeholder:"Singular",onChange:e=>{const t=Object.assign({},o,{singular:e});r(t)},label:"Unit name (singular)"})})}),(0,l.jsx)("div",{className:"tiered-pricing-product-editor-column",children:(0,l.jsx)(n.BaseControl,{id:"tiered_pricing_advanced_options_base_unit_name_plural",children:(0,l.jsx)(n.__experimentalInputControl,{value:o.plural||"",placeholder:"Plural",label:"Unit name (plural)",onChange:e=>{const t=Object.assign({},o,{plural:e});r(t)}})})})]})})})}const p=JSON.parse('{"$schema":"https://schemas.wp.org/trunk/block.json","apiVersion":3,"name":"tiered-pricing-table/product-editor-advance-product-options","version":"0.1.0","title":"Woo blocks-based product editor: Advanced Product Options","category":"widgets","icon":"flag","description":"A block to allow users to update advanced product options related to the tiered pricing plugin.","attributes":{"message":{"type":"string","__experimentalRole":"content","source":"text","selector":"div"},"availableRoles":{"type":"object","default":[]},"availableLayouts":{"type":"object","default":[]}},"supports":{"html":false,"inserter":false},"textdomain":"test-editor","editorScript":"file:./index.js","editorStyle":"file:./index.css","style":"file:./style-index.css"}');(0,o.registerBlockType)(p,{edit:function({attributes:e,context:t}){const o=(0,r.useWooBlockProps)(e);return(0,l.jsxs)("div",{...o,children:[(0,l.jsx)(n.PanelRow,{children:(0,l.jsx)(c,{context:t,attributes:e})}),(0,l.jsx)(n.PanelRow,{children:(0,l.jsx)(d,{context:t,attributes:e})})]})}})})();