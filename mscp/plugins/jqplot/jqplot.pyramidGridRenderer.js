/* jqPlot 1.0.8r1250 | (c) 2009-2013 Chris Leonello | jplot.com
   jsDate | (c) 2010-2013 Chris Leonello
 */(function(a){a.jqplot.PyramidGridRenderer=function(){a.jqplot.CanvasGridRenderer.call(this)};a.jqplot.PyramidGridRenderer.prototype=new a.jqplot.CanvasGridRenderer();a.jqplot.PyramidGridRenderer.prototype.constructor=a.jqplot.PyramidGridRenderer;a.jqplot.CanvasGridRenderer.prototype.init=function(c){this._ctx;this.plotBands={show:false,color:"rgb(230, 219, 179)",axis:"y",start:null,interval:10};a.extend(true,this,c);var b={lineJoin:"miter",lineCap:"round",fill:false,isarc:false,angle:this.shadowAngle,offset:this.shadowOffset,alpha:this.shadowAlpha,depth:this.shadowDepth,lineWidth:this.shadowWidth,closePath:false,strokeStyle:this.shadowColor};this.renderer.shadowRenderer.init(b)};a.jqplot.PyramidGridRenderer.prototype.draw=function(){this._ctx=this._elem.get(0).getContext("2d");var D=this._ctx;var G=this._axes;var q=G.xaxis.u2p;var J=G.yMidAxis.u2p;var l=G.xaxis.max/1000;var u=q(0);var f=q(l);var r=["xaxis","yaxis","x2axis","y2axis","yMidAxis"];D.save();D.clearRect(0,0,this._plotDimensions.width,this._plotDimensions.height);D.fillStyle=this.backgroundColor||this.background;D.fillRect(this._left,this._top,this._width,this._height);if(this.plotBands.show){D.save();var c=this.plotBands;D.fillStyle=c.color;var d;var o,n,p,I;if(c.axis.charAt(0)==="x"){if(G.xaxis.show){d=G.xaxis}}else{if(c.axis.charAt(0)==="y"){if(G.yaxis.show){d=G.yaxis}else{if(G.y2axis.show){d=G.y2axis}else{if(G.yMidAxis.show){d=G.yMidAxis}}}}}if(d!==undefined){var g=c.start;if(g===null){g=d.min}for(var H=g;H<d.max;H+=2*c.interval){if(d.name.charAt(0)==="y"){o=this._left;if((H+c.interval)<d.max){n=d.series_u2p(H+c.interval)+this._top}else{n=d.series_u2p(d.max)+this._top}p=this._right-this._left;I=d.series_u2p(g)-d.series_u2p(g+c.interval);D.fillRect(o,n,p,I)}}}D.restore()}D.save();D.lineJoin="miter";D.lineCap="butt";D.lineWidth=this.gridLineWidth;D.strokeStyle=this.gridLineColor;var L,K,A,C;for(var H=5;H>0;H--){var O=r[H-1];var d=G[O];var M=d._ticks;var B=M.length;if(d.show){if(d.drawBaseline){var N={};if(d.baselineWidth!==null){N.lineWidth=d.baselineWidth}if(d.baselineColor!==null){N.strokeStyle=d.baselineColor}switch(O){case"xaxis":if(G.yMidAxis.show){z(this._left,this._bottom,u,this._bottom,N);z(f,this._bottom,this._right,this._bottom,N)}else{z(this._left,this._bottom,this._right,this._bottom,N)}break;case"yaxis":z(this._left,this._bottom,this._left,this._top,N);break;case"yMidAxis":z(u,this._bottom,u,this._top,N);z(f,this._bottom,f,this._top,N);break;case"x2axis":if(G.yMidAxis.show){z(this._left,this._top,u,this._top,N);z(f,this._top,this._right,this._top,N)}else{z(this._left,this._bottom,this._right,this._bottom,N)}break;case"y2axis":z(this._right,this._bottom,this._right,this._top,N);break}}for(var E=B;E>0;E--){var v=M[E-1];if(v.show){var k=Math.round(d.u2p(v.value))+0.5;switch(O){case"xaxis":if(v.showGridline&&this.drawGridlines&&(!v.isMinorTick||d.showMinorTicks)){z(k,this._top,k,this._bottom)}if(v.showMark&&v.mark&&(!v.isMinorTick||d.showMinorTicks)){A=v.markSize;C=v.mark;var k=Math.round(d.u2p(v.value))+0.5;switch(C){case"outside":L=this._bottom;K=this._bottom+A;break;case"inside":L=this._bottom-A;K=this._bottom;break;case"cross":L=this._bottom-A;K=this._bottom+A;break;default:L=this._bottom;K=this._bottom+A;break}if(this.shadow){this.renderer.shadowRenderer.draw(D,[[k,L],[k,K]],{lineCap:"butt",lineWidth:this.gridLineWidth,offset:this.gridLineWidth*0.75,depth:2,fill:false,closePath:false})}z(k,L,k,K)}break;case"yaxis":if(v.showGridline&&this.drawGridlines&&(!v.isMinorTick||d.showMinorTicks)){z(this._right,k,this._left,k)}if(v.showMark&&v.mark&&(!v.isMinorTick||d.showMinorTicks)){A=v.markSize;C=v.mark;var k=Math.round(d.u2p(v.value))+0.5;switch(C){case"outside":L=this._left-A;K=this._left;break;case"inside":L=this._left;K=this._left+A;break;case"cross":L=this._left-A;K=this._left+A;break;default:L=this._left-A;K=this._left;break}if(this.shadow){this.renderer.shadowRenderer.draw(D,[[L,k],[K,k]],{lineCap:"butt",lineWidth:this.gridLineWidth*1.5,offset:this.gridLineWidth*0.75,fill:false,closePath:false})}z(L,k,K,k,{strokeStyle:d.borderColor})}break;case"yMidAxis":if(v.showGridline&&this.drawGridlines&&(!v.isMinorTick||d.showMinorTicks)){z(this._left,k,u,k);z(f,k,this._right,k)}if(v.showMark&&v.mark&&(!v.isMinorTick||d.showMinorTicks)){A=v.markSize;C=v.mark;var k=Math.round(d.u2p(v.value))+0.5;L=u;K=u+A;if(this.shadow){this.renderer.shadowRenderer.draw(D,[[L,k],[K,k]],{lineCap:"butt",lineWidth:this.gridLineWidth*1.5,offset:this.gridLineWidth*0.75,fill:false,closePath:false})}z(L,k,K,k,{strokeStyle:d.borderColor});L=f-A;K=f;if(this.shadow){this.renderer.shadowRenderer.draw(D,[[L,k],[K,k]],{lineCap:"butt",lineWidth:this.gridLineWidth*1.5,offset:this.gridLineWidth*0.75,fill:false,closePath:false})}z(L,k,K,k,{strokeStyle:d.borderColor})}break;case"x2axis":if(v.showGridline&&this.drawGridlines&&(!v.isMinorTick||d.showMinorTicks)){z(k,this._bottom,k,this._top)}if(v.showMark&&v.mark&&(!v.isMinorTick||d.showMinorTicks)){A=v.markSize;C=v.mark;var k=Math.round(d.u2p(v.value))+0.5;switch(C){case"outside":L=this._top-A;K=this._top;break;case"inside":L=this._top;K=this._top+A;break;case"cross":L=this._top-A;K=this._top+A;break;default:L=this._top-A;K=this._top;break}if(this.shadow){this.renderer.shadowRenderer.draw(D,[[k,L],[k,K]],{lineCap:"butt",lineWidth:this.gridLineWidth,offset:this.gridLineWidth*0.75,depth:2,fill:false,closePath:false})}z(k,L,k,K)}break;case"y2axis":if(v.showGridline&&this.drawGridlines&&(!v.isMinorTick||d.showMinorTicks)){z(this._left,k,this._right,k)}if(v.showMark&&v.mark&&(!v.isMinorTick||d.showMinorTicks)){A=v.markSize;C=v.mark;var k=Math.round(d.u2p(v.value))+0.5;switch(C){case"outside":L=this._right;K=this._right+A;break;case"inside":L=this._right-A;K=this._right;break;case"cross":L=this._right-A;K=this._right+A;break;default:L=this._right;K=this._right+A;break}if(this.shadow){this.renderer.shadowRenderer.draw(D,[[L,k],[K,k]],{lineCap:"butt",lineWidth:this.gridLineWidth*1.5,offset:this.gridLineWidth*0.75,fill:false,closePath:false})}z(L,k,K,k,{strokeStyle:d.borderColor})}break;default:break}}}v=null}d=null;M=null}D.restore();function z(j,i,e,b,h){D.save();h=h||{};if(h.lineWidth==null||h.lineWidth!=0){a.extend(true,D,h);D.beginPath();D.moveTo(j,i);D.lineTo(e,b);D.stroke()}D.restore()}if(this.shadow){if(G.yMidAxis.show){var F=[[this._left,this._bottom],[u,this._bottom]];this.renderer.shadowRenderer.draw(D,F);var F=[[f,this._bottom],[this._right,this._bottom],[this._right,this._top]];this.renderer.shadowRenderer.draw(D,F);var F=[[u,this._bottom],[u,this._top]];this.renderer.shadowRenderer.draw(D,F)}else{var F=[[this._left,this._bottom],[this._right,this._bottom],[this._right,this._top]];this.renderer.shadowRenderer.draw(D,F)}}if(this.borderWidth!=0&&this.drawBorder){if(G.yMidAxis.show){z(this._left,this._top,u,this._top,{lineCap:"round",strokeStyle:G.x2axis.borderColor,lineWidth:G.x2axis.borderWidth});z(f,this._top,this._right,this._top,{lineCap:"round",strokeStyle:G.x2axis.borderColor,lineWidth:G.x2axis.borderWidth});z(this._right,this._top,this._right,this._bottom,{lineCap:"round",strokeStyle:G.y2axis.borderColor,lineWidth:G.y2axis.borderWidth});z(this._right,this._bottom,f,this._bottom,{lineCap:"round",strokeStyle:G.xaxis.borderColor,lineWidth:G.xaxis.borderWidth});z(u,this._bottom,this._left,this._bottom,{lineCap:"round",strokeStyle:G.xaxis.borderColor,lineWidth:G.xaxis.borderWidth});z(this._left,this._bottom,this._left,this._top,{lineCap:"round",strokeStyle:G.yaxis.borderColor,lineWidth:G.yaxis.borderWidth});z(u,this._bottom,u,this._top,{lineCap:"round",strokeStyle:G.yaxis.borderColor,lineWidth:G.yaxis.borderWidth});z(f,this._bottom,f,this._top,{lineCap:"round",strokeStyle:G.yaxis.borderColor,lineWidth:G.yaxis.borderWidth})}else{z(this._left,this._top,this._right,this._top,{lineCap:"round",strokeStyle:G.x2axis.borderColor,lineWidth:G.x2axis.borderWidth});z(this._right,this._top,this._right,this._bottom,{lineCap:"round",strokeStyle:G.y2axis.borderColor,lineWidth:G.y2axis.borderWidth});z(this._right,this._bottom,this._left,this._bottom,{lineCap:"round",strokeStyle:G.xaxis.borderColor,lineWidth:G.xaxis.borderWidth});z(this._left,this._bottom,this._left,this._top,{lineCap:"round",strokeStyle:G.yaxis.borderColor,lineWidth:G.yaxis.borderWidth})}}D.restore();D=null;G=null}})(jQuery);