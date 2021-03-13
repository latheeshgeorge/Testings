/*
 * colorPicker, JavaScript Color Picker (one-file version)
 * http://dematte.at/colorPicker
 *
 * Copyright 2011 by Peter Dematté
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * @version 0.91
 * @created 2010-11-03
 * @updated 2011-07-13
 * Date: Mon Aug 15 12:02:56 2011 -0100
 */ 
function colorPicker(ae, i, f, J, aa, U, o, v, S, Q, t, ag, m, C, W, q, c, V) {
	    var Z = colorPicker,
        i = i || Z.mode || "B",
        f = f || Z.size || 4,
        J = J != undefined ? J : Z.allowResize != undefined ? Z.allowResize : false,
        aa = aa != undefined ? aa : Z.allowClose != undefined ? Z.allowClose : true,
        U = U != undefined ? U : Z.allowDrag != undefined ? Z.allowDrag : false,
        o = o != undefined ? o : Z.expColor != undefined ? Z.expColor : true,
        v = v != undefined ? v : Z.expHEX != undefined ? Z.expHEX : true,
        S = S || Z.offsetX || 0,
        Q = Q || Z.offsetY || 3,
        t = Z.orientation || ["bottom", "left"],
        ag = ag || Z.parentObj || null,
        m = m || Z.parentXY || "",
        C = C || Z.objs || [204, 0, 0],
        W = W || Z.difPad || 2,
        q = q != undefined ? q : Z.rSpeed != undefined ? Z.rSpeed : 15,
        c = c || Z.cookieLife || 0,
        V = V || Z.docBody || document,
        Z = colorPicker.cP,
        Y, ac, b, G, D = 1,
        B = 1,
        H, R, O, N, u, g = navigator.userAgent.toLowerCase().search(/(iphone|mobi)/) !== -1,
        s = document.createStyleSheet && document.getElementById,
        n = (!Z) ?
    function (ao) {
        var ah = document.getElementsByTagName("script"),
            aq = document.createElement("div"),
            ap = document.createElement("img"),
            x = document.createElement("style"),
            au = s && document.querySelectorAll,
            ay = s && !document.compatMode,
            aw = s && !ay && !window.XMLHttpRequest,
            aB, aC, aj, al, am, ax = '<div class="cPSkin" style="z-index:1000000;"><input class="cPdummy" type="" /><div class="cPSkinC01"></div><div class="cPSkinC02"></div><div class="cPSkinC03"></div><div class="cPSkinC04"></div><div class="cPSkinS01"></div><div class="cPSkinS02"></div><div class="cPSlides"><div class="cPSL1"></div><span class="cPSL2"></span><span class="cPSL3"></span><div class="cPSLC"></div><span class="cPSL4"></span><span class="cPSR1"></span><span class="cPSR2"></span><div class="cPSR3"></div><div class="cPSRCL"></div><div class="cPSRCR"></div><span class="cPSR5"></span></div><div class="cPMemory"><div class="cPM1"></div><div class="cPM2"></div><div class="cPM3 ext"></div><div class="cPM4"></div><div class="cPM5"></div><div class="cPM6"></div><div class="cPM7 ext"></div><div class="cPM8"></div><div class="cPM9"></div><div class="cPM0"></div></div><div class="cPPanel"><div class="cPHSB"><div class="cPBLH bUp">H</div><input type="text" name="cPIH" value="0" maxlength="3" /><div class="cPBRH noB">&deg;</div><div class="cPBLS bUp">S</div><input type="text" name="cPIS" value="0" maxlength="3" /><div class="cPBRS noB">%</div><div class="cPBLV bUp">B</div><input type="text" name="cPIV" value="0" maxlength="3" /><div class="cPBRV noB">%</div></div><div class="cPRGB"><div class="cPBLR bUp">R</div><input type="text" name="cPIR" value="0" maxlength="3" /><div class="cPBRR bDown">&nbsp;</div><div class="cPBLG bUp">G</div><input type="text" name="cPIG" value="0" maxlength="3" /><div class="cPBRG bDown">&nbsp;</div><div class="cPBLB bDown">B</div><input type="text" name="cPIB" value="0" maxlength="3" /><div class="cPBRB bDown">&nbsp;</div></div><div class="cPCMYK"><div class="cPBLC noB">C</div><input type="text" name="cPIC" value="0" readonly="readonly" /><div class="cPBRC noB">%</div><div class="cPBLM noB">M</div><input type="text" name="cPIM" value="0" readonly="readonly" /><div class="cPBRM noB">%</div><div class="cPBLY noB">Y</div><input type="text" name="cPIY" value="0" readonly="readonly" /><div class="cPBRY noB">%</div><div class="cPBLK noB">K</div><input type="text" name="cPIK" value="100" readonly="readonly" /><div class="cPBRK noB">%</div></div><div class="cPHEX"><div class="cPBLX noB">#</div><input type="text" name="cPIX" value="000000" maxlength="6" /><div class="cPBRX bUp">W</div></div><div class="cPCTRT"></div><div class="cPCD"></div><div class="cPControl"><div class="cPCB1 bUp"></div><div class="cPCB2 bUp"></div><div class="cPCB3 bUp">RES</div><div class="cPCB4 bUp">SAVE</div></div></div><div class="cPClose"></div><div class="cPResize"></div><div class="cPResizer"><div class="cPOP30"></div></div></div>',
            aE = '.cPSkin{position:absolute;width:407px;height:302px;text-align:left}.cPSkinC01,.cPSkinC02,.cPSkinC03,.cPSkinC04{position:absolute;width:8px;height:8px;background:url(_icons.png) right top}.cPSkinC02{right:0}.cPSkinC03{bottom:0}.cPSkinC04{bottom:0;right:0}.cPSkinS01,.cPSkinS02{position:absolute;width:100%;height:100%;background-color:#444}.cPSkinS01{left:4px;width:399px}.cPSkinS02{top:4px;height:294px}.cPSlides{position:absolute;left:9px;top:9px;width:284px;height:256px;overflow:hidden}.cPSL2R,.cPSL3R,.cPSL2G,.cPSL3G,.cPSL2B,.cPSL3B,.cPSL1H,.cPSL2H,.cPSL3H,.cPSL1S,.cPSL2S,.cPSL3S,.cPSL1V,.cPSL2V,.cPSL3V{position:absolute;width:256px;height:256px}.cPSL2R,.cPSL3R,.cPSL2G,.cPSL3G,.cPSL2B,.cPSL3B{background:url(_patches.png)}.cPSLCB,.cPSLCW{position:absolute;width:11px;height:11px;font-size:0px;background:url(_icons.png)}.cPSLCW{background-position:0 31px}.cPSLCB{background-position:0 11px}.cPSL4{position:absolute;width:256px;height:256px;cursor:crosshair}.cPSL4NC{cursor:url(_blank.cur),crosshair}.cPSR1R,.cPSR2R,.cPSR3R,.cPSR4R,.cPSR1G,.cPSR2G,.cPSR3G,.cPSR4G,.cPSR1B,.cPSR2B,.cPSR3B,.cPSR4B,.cPSR3H,.cPSR1S,.cPSR2S,.cPSR1V,.cPSR2V,.cPSR4V{position:absolute;right:0px;width:28px;height:256px;background:url(_vertical.png)}.cPSR4S,.cPSR5{position:absolute;right:0;width:28px;height:256px}.cPSRCLB,.cPSRCRB,.cPSRCLW,.cPSRCRW{position:absolute;width:4px;height:7px;font-size:0px;right:0;background:url(_icons.png)}.cPSRCLB,.cPSRCLW{right:24px}.cPSRCLW{background-position:-15px 0}.cPSRCLB{background-position:-26px 0}.cPSRCRW{background-position:0 0}.cPSRCRB{background-position:-11px 0}.cPMemory{position:absolute;left:9px;bottom:9px;width:285px;height:28px;display:inline-block;vertical-align:top}.cPM1,.cPM2,.cPM3,.cPM4,.cPM5,.cPM6,.cPM7,.cPM8,.cPM9,.cPM0{width:28px;height:27px;float:left;background-color:#000;margin-right:1px;margin-top:1px}.cPM1,.cPM3,.cPM5,.cPM7,.cPM9{width:27px}.cPM0{height:28px;margin-top:0;background:#000 url(_icons.png) no-repeat 9px -7px}.cPM0B{background-position:9px -27px}.cPPanel{position:absolute;width:94px;height:282px;top:9px;right:9px!important;right:8px;border:1px solid #222;border-right:1px solid #555;border-bottom:1px solid #555;font:normal normal normal 12px/11px "Courier New",Courier,mono;color:#ddd;background-color:#333}.cPHSB,.cPRGB,.cPCMYK,.cPHEX{border-top:1px solid #444;border-bottom:1px solid #222;display:inline-block;vertical-align:top;padding:2px 0 4px; margin:0px 4px}.cPHSB{border-top:none}.cPHEX{border-bottom:0}.cPPanel input{padding:1px;border:1px solid #222;background-color:#333;float:left;font:normal normal normal 12px/10px "Courier New",Courier,mono;color:#ccc;border-right-color:#555;border-bottom-color:#555;line-height:12px;width:44px;height:12px;margin:2px 2px 0}.cPHSB div,.cPRGB div,.cPCMYK div,.cPHEX div{width:15px;height:14px;border:1px solid #222;border-left-color:#555;border-top-color:#555;float:left;text-align:center;cursor:default;line-height:13px;margin:2px 0 0}.cPPanel .noB{border:1px solid #333}.cPPanel .bUp{border-left-color:#555;border-top-color:#555}.cPPanel .bDown{border:1px solid #555;border-left-color:#222;border-top-color:#222;background-color:#444}.cPCTRT,.cPCD{position:absolute;height:3px;font-size:0;overflow:hidden;left:0;bottom:53px;background-color:#CCC;border-right:1px solid #333;border-bottom:1px solid #222;border-left:1px solid #333;z-index:1}.cPCD{background-color:#C00}.CTRTop{background-color:transparent;z-index:2}.cPCD1{background-color:#FF9900}.cPCD2{background-color:#44DD00}.cPControl{position:absolute;bottom:0;left:0}.cPCB1,.cPCB2,.cPCB3,.cPCB4{width:45px;height:24px;float:left;border:1px solid #555;border-bottom-color:#222;border-right-color:#222;text-align:center;line-height:23px;cursor:default}.cPCB3,.cPCB4{height:25px}.cPClose{position:absolute;right:2px;top:2px;width:15px;height:15px;background:url(_icons.png) -30px 0}.cPResize{position:absolute;right:2px;bottom:2px;width:15px;height:15px;background:url(_icons.png) -45px 0;cursor:se-resize}.cPResizer{border:1px dashed #555;position:absolute;left:-1px;top:-1px;width:100%;height:100%;display:none;z-index:3}.cPResizer div{width:100%;height:100%;background-color:#bbb}.S{width:263px;height:159px}.S .cPSkinS01{width:255px}.S .cPSkinS02{height:151px}.S .cPSlides{width:143px;height:128px;left:8px;top:8px}.S .cPSL2R,.S .cPSL3R,.S .cPSL2G,.S .cPSL3G,.S .cPSL2B,.S .cPSL3B,.S .cPSL1H,.S .cPSL2H,.S .cPSL3H,.S .cPSL1S,.S .cPSL2S,.S .cPSL3S,.S .cPSL1V,.S .cPSL2V,.S .cPSL3V,.S .cPSL4{width:128px;height:128px}.S .cPSR1R,.S .cPSR2R,.S .cPSR3R,.S .cPSR4R,.S .cPSR1G,.S .cPSR2G,.S .cPSR3G,.S .cPSR4G,.S .cPSR1B,.S .cPSR2B,.S .cPSR3B,.S .cPSR4B,.S .cPSR3H,.S .cPSR1S,.S .cPSR2S,.S .cPSR4S,.S .cPSR1V,.S .cPSR2V,.S .cPSR4V,.S .cPSR5{width:15px;height:128px;right:0px!important;right:-1px}.S .cPSRCLB,.S .cPSRCLW{right:12px!important;right:11px;width:3px;background-position:-27px 0}.S .cPSRCLW{background-position:-16px 0}.S .cPSRCRB,.S .cPSRCRW{right:-1px!important;right:-2px}.S .cPMemory{height:15px;width:144px;bottom:8px;left:8px}.S .cPMemory div{height:14px;width:13px}.S .cPMemory .ext{width:14px}.S .cPMemory .cPM0{width:15px;height:15px;background-position:2px -14px}.S .cPMemory .cPM0B{width:15px;height:15px;background-position:2px -34px}.S .cPPanel{height:141px!important;height:142px;top:8px;right:8px!important;right:7px}.S .cPRGB{border-top:0;padding-top:2px}.S .cPCMYK{display:none}.S .cPClose{right:1px;top:1px}.S .cPResize{right:1px;bottom:1px}.XS{width:155px;height:155px}.XS .cPSkinS01{width:147px}.XS .cPSkinS02{height:147px}.XS .cPSlides{left:6px;top:6px}.XS .cPMemory{bottom:6px;left:6px}.XS .cPPanel{display:none}.XS .cPClose,.XS .cPResize{background-position:24px;right:-3px;bottom:-6px;width:9px;height:14px}.XS .cPClose{top:-2px}.XXS{width:151px;height:87px}.XXS .cPSkinS01{width:143px}.XXS .cPSkinS02{height:79px}.XXS .cPSlides{left:4px;top:4px;width:143px;height:64px}.XXS .cPSL1S,.XXS .cPSL2S,.XXS .cPSL3S,.XXS .cPSL1V,.XXS .cPSL2V,.XXS .cPSL3V,.XXS .cPSL4,.XXS .cPSR1S,.XXS .cPSR2S,.XXS .cPSR4S,.XXS .cPSR1V,.XXS .cPSR2V,.XXS .cPSR4V,.XXS .cPSR5{height:64px}.XXS .cPMemory{bottom:4px;left:4px}.cPSR1R,.cPSR1G,.cPSR1B,.cPSR2V,.cPSL3H,.cPSL2S{background:url(_vertical.png) 0 -2432px}.cPSR1R{background-color:#f00}.cPSR2R{background-position:0 -4480px}.cPSR3R{background-position:0 -2944px}.cPSR4R{background-position:0 -3202px}.cPSR1G{background-color:#0f0}.cPSR2G{background-position:0 -3968px}.cPSR3G{background-position:0 -4736px}.cPSR4G{background-position:0 -3712px}.cPSR1B{background-color:#00f}.cPSR2B{background-position:0 -4224px}.cPSR3B{background-position:0 -3456px}.cPSR4B{background-position:0 -2688px}.cPSL2R{background-position:-1664px 0}.cPSL3R{background-position:-896px 0}.cPSL2G,.S .cPSL2H{background-position:-640px 0}.cPSL3G{background-position:-384px 0}.cPSL2B{background-position:-1152px 0}.cPSL3B{background-position:-1408px 0}.cPSR3H{background-position:0 -1664px}.cPSR4S{background:#000 none}.cPSR4V,.cPSL3S{background:url(_vertical.png) 0 -2176px}.cPSL1H{background:none}.cPSL2H{background:url(_horizontal.png) 0 0}.cPSL1S,.cPSL1V{background:url(_horizontal.png) -256px 0}.cPSL2V,.cPSR2S{background:url(_vertical.png) 0 -1920px}.cPSL3V{background:#000}.S .cPSR1R,.S .cPSR2V,.S .cPSR1G,.S .cPSR1B,.S .cPSL3H,.S .cPSL2S{background-position:0 -1408px}.S .cPSR2R{background-position:0 -896px}.S .cPSR3R,.S .cPSL3B{background-position:0 -128px}.S .cPSR4R{background-position:0 -256px}.S .cPSR2G{background-position:0 -640px}.S .cPSR3G{background-position:0 -1024px}.S .cPSR4G{background-position:0 -512px}.S .cPSR2B{background-position:0 -768px}.S .cPSR3B{background-position:0 -384px}.S .cPSR4B,.S .cPSL3R{background-position:0 0}.S .cPSL2R{background-position:-128px -128px}.S .cPSL2G{background-position:-256px -128px}.S .cPSL3G{background-position:-256px 0}.S .cPSL2B{background-position:-128px 0}.S .cPSR3H{background-position:0 -1536px}.S .cPSR2S,.S .cPSL2V{background-position:0 -1152px}.S .cPSR4V,.S .cPSL3S{background-position:0 -1280px}.S .cPSL1S,.S .cPSL1V{background-position:-512px 0}.XXS .cPSR2S,.XXS .cPSL2V{background-position:0 -4992px}.XXS .cPSR2V,.XXS .cPSL2S{background-position:0 -5120px}.XXS .cPSR4V,.XXS .cPSL3S{background-position:0 -5056px}.cPhide{display:none}.cPdummy{position:absolute;left:4px;top:4px;width:10px}.cPinpDrag{background:url(_icons.png) no-repeat -26px -17px}.cPinpDragOn{background:url(_icons.png) no-repeat -26px -30px}',
            y = "iVBORw0KGgoAAAANSUhEUgAA",
            aD = y + "AAEAAAABCAYAAAAfFcSJAAAADUlEQVQI12P4//8/MwAI/wMBbrqo4gAAAABJRU5ErkJggg==",
            ai = y + "AwAAAAABCAYAAABpNcm2AAAAhElEQVR4Xu2QwQrCQAxEnwuVUv3/L9UiLZsq5LQXhy1BD9nAMGGGhGQux6fwspQ8uCb+f/CyHEzAtYXQtB6/Eyuw0WJ3FrrQ4neGBzBCDUjkR/MF63n0/6HeHdxa9r7ftxkeOJ40vdC++mcO1DOvog7o9qc9PFJmTB11zl9XAEuMmvn/NznMxg7wvKXZAAAAAElFTkSuQmCC",
            ak = y + "AAEAABRACAYAAABIgKWdAAABmUlEQVR42u3c0U7qQBCA4b+jJSfC+z8pJIbCjhdVVGxNOdYWs//Nl2FnMyGbpbtb0lIyM6AgsgJNJlFAZB2cf7IeCS4EshZkZgYFRJYHnH+yIpmuv7IyboRlHchsXH9ltf1fZhJAyP/y4Bj8CKKUIrIsNE2TZCb4G/T6tzgAGfn0RMCWgN1YtJuryzNB7IE4wJfo9sRol66FYAfB9hJdfZyQmNDlXyFgf+HwbfTDLlsgdjAcjSZu7xLPEOwhOAxEo4kJXa4+dhDQErAZoJ3YtrmvKoUgjvCFbmLbjIkZq9BCsPnMUNuMiV+uEoWA4wDdxLYZE7NVaYHYfGaobcbEL1eJAsFxgG5i2+2Jeap8V/5y/uXDccTIaMHowTEwWjEiMlNEpB4A8n3/h6uBkfs/owqi/tZz/9dbf/0TEamOJt8eAhERqY10DESkSl4Pxm6ERaQ6skmCRESkNhrHQEQqJT3/ikjVh2BvhIpIdby+f8iNsIhUB5n5GMAQMZZYlvY+vsY1/auLz+czcTqd5Fb654/uZ5r9ufkH5Av1TLR7OwfhYAAAAABJRU5ErkJggg==",
            an = y + "AEgAAAAvCAQAAADqFUQuAAAB7klEQVRYw+2YP26DMBTGnxBdq66VKhaQ+kei3CBFxBwgA2MHVs6QU2RhYcwtWHIXDvL6gAA2DiQpEDtS/MmyebbwT8+fTRRAqITHFoSCXZxXM43hsBA8Tu/4K403io0g9Qu/CNLYqN5d4XAT2vWOAyeB8BwQww7ng3D8AaDYWB2a2OoQG+2qvSU5UhmpaS4D+pSywwMFKR8N0nbNk0vi+Cj/qq3U6zYrEGY6ApBf8GN+MWOGtj2wEudrAGccaDYPbTmcEki2snPBlvVOGU44ZSLQG+H8DOCMm/rf+h7dsqHsiB5iwrFnMAmIPXutQ2RTByM4DIfeOQ2I5CG7QM6gh2YHYq8erq/GWRKoytL6ChxneSD24l2JszQQsCevZ+JzWhqIFM2AMxFoCdWNiwlmmFNNqK8cKMQdRmijSTWifqgWyCUEiwtZ9OyqBEooK2IwophCoIw2SgzaFLsdAJVN9wOMmpy8I04yKXZDoA2WAl0yVOOU0tZD2p0yxfeQvGWKb2rZ1Kq/XdKx1/Lj+gB6AN050B61AtpjKW2AapwOqS4uJJBBTjWhPoDiizKEHURgg0k1on6oFsglBIv7T8iiZ1flliWUFbFEkCg0NfnG7gHZkCk89mRlswdkQq7SQxpkSARS7iHNTtkd3EOa3dR/zD8stDEYhk8AAAAASUVORK5CYII=",
            aA = y + "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";
        for (var ar = 0; ar < ah.length; ar++) {
            if (ah[ar].src.indexOf("colorPicker.js") !== -1) {
                aB = ah[ar].src.substring(0, ah[ar].src.lastIndexOf("/") + 1)
            }
        }
        if (/^\//.exec(aB)) {
            aB = location.href.split("/")[0] + "//" + location.href.split("/")[2] + aB
        } else {
            if (!/:\/\//.exec(aB)) {
                aB = location.href.substring(0, location.href.lastIndexOf("/") + 1) + aB
            }
        }
        aq.innerHTML = ax.replace(/src=\"/g, 'src="' + aB);
        Z = colorPicker.cP = (ag || ao.parentNode).appendChild(aq.getElementsByTagName("div")[0]);
        Z.cPSkins = Z.style;
        Z.but = [];
        aq = Z.all || Z.getElementsByTagName("*");
        for (var ar = 0, az, e, at = 0; ar < aq.length; ar++, az = null) {
            if (aq[ar].className) {
                az = aq[ar].className.replace(/(.*?)\s.*/, "$1")
            }
            if (aq[ar].name) {
                az = aq[ar].name.replace(/(.*?)\s.*/, "$1")
            }
            if (az) {
                Z[az] = aq[ar];
                if (!aq[ar].name && aq[ar].className.search(/cP(B|P|Sl|d|Sk|RGB|HSB|CMYK|HEX|Mem|Cont|CB(3|4)|SL[2-3]|SR(1|3))/)) {
                    Z[az + "s"] = aq[ar].style
                } else {
                    if (e = /cPB(.)(.)\s+b/.exec(aq[ar].className)) {
                        Z.but[at] = [az, e[1], e[2], at++]
                    }
                }
            }
        }
        aC = "mhtml:" + aB + "IE.mht!";
        aj = "data:image/png;base64,";
        if (s) {
            ap.src = aC + "_horizontal"
        }
        if (!s || au || ap.height == 1) {
            aE = aE.replace(/url\(_[a-z]*\.png\);*/g, "") + ".cPSkinC01,.cPSkinC02,.cPSkinC03,.cPSkinC04,.cPSLCB,.cPSLCW,.cPSRCLB,.cPSRCRB,.cPSRCLW,.cPSRCRW,.cPM0,.cPClose,.cPResize,.cPinpDrag,.cPinpDragOn{background-image:url(" + (s && !au ? aC + "_icons" : aj + an) + ")}.cPSL2R,.cPSL3R,.cPSL2G,.cPSL3G,.cPSL2B,.cPSL3B{background-image:url(" + (s && !au ? aC + "_patches" : aj + aA) + ")}.cPSR1R,.cPSR2R,.cPSR3R,.cPSR4R,.cPSR1G,.cPSR2G,.cPSR3G,.cPSR4G,.cPSR1B,.cPSR2B,.cPSR3B,.cPSR4B,.cPSR3H,.cPSR2V,.cPSL3H,.cPSL2S,.cPSR4V,.cPSL3S,.cPSL2V,.cPSR2S{background-image:url(" + (s && !au ? aC + "_vertical" : aj + ak) + ")}.cPSL2H,.cPSL1S,.cPSL1V{background-image:url(" + (s && !au ? aC + "_horizontal" : aj + ai) + ")}"
        }
        for (ar = 0, al = []; ar <= 100; ar++) {
            al[ar] = ".cPOP" + ar + (s ? (au ? '{-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=' + ar + ')";' : "{") + "filter:alpha(opacity=" + ar + ")}" : "{opacity:." + (ar <= 9 ? 0 : "") + ar + "}")
        }
        aE = aE.replace("_blank.cur", !ay && !aw ? (s ? aC + "_IECur" : aj + aD) : "_blank.cur").replace(/url\(_/g, "url(" + aB + "_") + al.join("").replace(/\opacity:.100/, "opacity:1");
        if (!s && document.compatMode == "BackCompat") {
            aE += ".cPPanel input{height:16px;width:48px}"
        }
        x.setAttribute("type", "text/css");
        if (!x.styleSheet) {
            x.appendChild(document.createTextNode(aE))
        }
        document.getElementsByTagName("head")[0].appendChild(x);
        al = document.styleSheets[document.styleSheets.length - 1];
        if (x.styleSheet) {
            al.cssText = aE
        }
        Z.CTRTop = (Z.CTRTop = /\.CTRTop\s*{(.*?)}/.exec(aE)) ? Z.CTRTop[1] : "";
        Z.cPCD1 = (Z.cPCD1 = /\.cPCD1\s*{(.*?)}/.exec(aE)) ? Z.cPCD1[1] : "";
        Z.cPCD2 = (Z.cPCD2 = /\.cPCD2\s*{(.*?)}/.exec(aE)) ? Z.cPCD2[1] : "";
        if (ay || aw || (s && document.compatMode == "BackCompat")) {
            aE = [];
            ar = 0;
            aE[ar++] = ".cPClose,.cPResize,.cPSkinC01,.cPSkinC02,.cPSkinC03,.cPSkinC04,.cPinpDrag,.cPinpDragOn,.cPSRCLB,.cPSRCRB,.cPSRCLW,.cPSRCRW,.cPSLCB,.cPSLCW,.cPM0{background-image:url(" + aB + "_icons." + (!ay && !aw ? "png" : "gif") + ")}.cPSkinC03,.cPSkinC04{bottom:-7px}" + (au ? ".cPSkinC02,.cPSkinC04{right:-1px}" : "") + ".S{height:160px}.S .cPSlides{width:143px}.S .cPMemory{bottom:8px}.XS{height:156px}.XS .cPMemory{bottom:6px}.XXS{height:88px}.XXS .cPMemory{bottom:4px}";
            if (ay || document.compatMode == "BackCompat") {
                aE[ar++] = ".cPPanel{width:96px;height:284px}.cPPanel input{height:16px;width:48px}.cPHSB div,.cPRGB div,.cPCMYK div,.cPHEX div{width:17px;height:16px}.cPCB1,.cPCB2,.cPCB3,.cPCB4{width:47px;height:26px}.cPCTRT,.cPCD{height:4px;margin-bottom:1px}.S .cPPanel{height:143px}.S .cPControl{bottom:-1px}.S .cPControl{bottom:-1px}.S.cPCTRT,.S.cPCD{bottom:51px}.S .cPResize{bottom:2px}"
            }
            aE[ar++] = ".cPSR1R,.cPSR1G,.cPSR1B,.cPSR2V,.cPSL3H,.cPSL2S{top:-2432px;height:5184px}.cPSR2S,.cPSL2V{top:-1920px;height:5184px}.cPSL2H{left:0;width:768px}.S .cPSR1R,.S .cPSR1G,.S .cPSR1B,.S .cPSR2V,.S .cPSL3H,.S .cPSL2S{top:-1408px;height:5184px}.S .cPSR2S,.S .cPSL2V{top:-1152px;height:5184px}.S .cPSL2H{left:-640px;width:768px}.XXS .cPSR2S,.XXS .cPSL2V{top:-4992px;height:5184px}.XXS .cPSR2V,.XXS .cPSL2S{top:-5120px;height:5184px}.cPSR1H,.cPSR2H{display:none}";
            am = "filter: progid:DXImageTransform.Microsoft.AlphaImageLoader (sizingMethod='scale', src='";
            aE[ar++] = ".cPSR1R,.cPSR1G,.cPSR1B,.cPSR2S,.cPSR2V,.cPSL3H,.cPSL2S,.cPSL2V{" + (ap.height == 1 ? aC + "_vertical" : am + aB + "_vertical.png") + "');background-image:none}.cPSL2H{" + (ap.height == 1 ? aC + "_horizontal" : am + aB + "_horizontal.png") + "');background-image:none}";
            al.cssText += aE.join("");
            for (var av = Z.getElementsByTagName("span"), az, z; 0 < av.length;) {
                z = document.createElement("img");
                z.className = av[0].className;
                az = z.className.replace(/(.*?)\s.*/, "$1").replace("R4", "R3");
                z.src = aB + "_blank.gif";
                av[0].swapNode(z);
                Z[az] = z;
                Z[az + "s"] = z.style
            }
        }
        Z.onmousedown = function (aT) {
            var aT = aT || window.event,
                aJ = aT.target || aT.srcElement,
                aH = (aJ == Z.cPSL4),
                aW, aR, aX, aM = Z.className,
                aF;
            if (V.funcCache) {
                ab()
            }
            if (!g && (!aJ.name || !aJ.name.search(/PI[RGBHSVX]/))) {
                F()
            }
            if ((!aJ.className.search(/(cP(HSB|RGB|CMYK|HEX|Panel|Skin)|(.*?\snoB))/) || (aJ.name && !aJ.name.indexOf("cPI") && aJ.readOnly)) && U) {
                aR = p(aT, r(Z)), aW = r(Z.parentNode);
                h(V, "mousemove", function (aZ) {
                    var aY = p(aZ, aW);
                    if (s && (!this.skip ? this.skip = 1 : this.skip++) % 2) {
                        return false
                    }
                    Z.cPSkins.cssText = "left:" + (aY[0] - aR[0]) + "px;top:" + (aY[1] - aR[1]) + "px";
                    return false
                })
            } else {
                if (aJ == Z.cPResize && J) {
                    aW = r(Z);
                    if (g) {
                        aX = aM == "cPSkin" ? " S" : aM == "cPSkin S" ? " S XS" : aM == "cPSkin S XS" ? " S XS XXS" : "";
                        A(aX)
                    } else {
                        h(V, "mousemove", function (aZ) {
                            var aY = p(aZ, aW);
                            Z.cPResizers.cssText = "display:block;width:" + ((aY[0] < 3 ? 3 : aY[0]) + 5) + "px;height:" + ((aY[1] < 3 ? 3 : aY[1]) + 5) + "px";
                            aX = (aY[1] < 87) ? " S XS XXS" : (aY[0] < 180) ? " S XS" : (aY[0] < 275 || aY[1] < 175) ? " S" : "";
                            if (Z.className != "cPSkin" + aX) {
                                A(aX)
                            }
                            return false
                        })
                    }
                } else {
                    if (!aJ.className.search(/cPS[L|R]/)) {
                        if (Z.WEBS1) {
                            Z.WEBS1 = null
                        }
                        aW = r(aJ);
                        k(aT, aW, aH);
                        if (aH) {
                            Z.cPSL4.className = "cPSL4 cPSL4NC"
                        }
                        h(V, "mousemove", function (aY) {
                            k(aY, aW, aH);
                            return false
                        });
                        if (q) {
                            b = setInterval(function () {
                                j(aH)
                            }, q)
                        } else {
                            b = true
                        }
                    } else {
                        if (aF = /cPCB(\d+).*?/.exec(aJ.className)) {
                            if (aF[1] == 1) {
                                M(I(n.hsv[0] + (n.hsv[0] > 127.5 ? -127.5 : 127.5), n.hsv[1], n.hsv[2], true))
                            } else {
                                if (aF[1] == 2) {
                                    M(d(n.CB2Color))
                                } else {
                                    if (aF[1] == 3) {
                                        Z.cPCB2s.backgroundColor = "rgb(" + Z.cObj.color + ")";
                                        n.CB2Color = Z.cObj.color;
                                        n.iCtr = K(n.CB2Color);
                                        j(true, true)
                                    } else {
                                        Z.cPCB2s.backgroundColor = "rgb(" + n.rgbRND + ")";
                                        n.CB2Color = n.rgbRND;
                                        M(n.rgbRND)
                                    }
                                }
                            }
                            E(aJ, false)
                        } else {
                            if (aF = /cPB(.)(.)\s+b/.exec(aJ.className)) {
                                if (aF[1] == "L") {
                                    n.mode = aJ.className.substr(4, 1);
                                    M(n.rgb)
                                }
                                if (aF[1] == "R") {
                                    if (aF[2] != "X") {
                                        aF = aJ.className.substr(4, 1);
                                        n.modeRGB[aF] = !n.modeRGB[aF];
                                        E(aJ, n.modeRGB[aF]);
                                        l("CP.modeRGB." + aF, n.modeRGB[aF]);
                                        M(n.rgb)
                                    } else {
                                        var aN = n.rgbRND[0],
                                            aS = n.rgbRND[1],
                                            aU = n.rgbRND[2],
                                            aL = (aN * (256 * 256) + aS * 256 + aU) % 17 ? 17 : 51,
                                            aK = (aL - 1) / 2;
                                        Z.WEBS1 = Z.WEBS1 || n.rgbRND;
                                        if (Z.cPBRX.firstChild.data == "W") {
                                            M(Z.WEBS1)
                                        } else {
                                            M([aN + (aN % aL > aK ? aL : 0) - aN % aL, aS + (aS % aL > aK ? aL : 0) - aS % aL, aU + (aU % aL > aK ? aL : 0) - aU % aL])
                                        }
                                        E(aJ, false)
                                    }
                                }
                            } else {
                                if (aJ == Z.cPClose) {
                                    ad(true)
                                } else {
                                    if (aF = /cPM([0-9]).*/.exec(aJ.className)) {
                                        if (aF[1] != "0") {
                                            M(d(aJ.style.backgroundColor))
                                        } else {
                                            var aQ = "rgb(" + n.rgbRND + ")",
                                                aF;
                                            for (var aP = 1; aP < 9; aP++) {
                                                aF = "cPM" + aP + "s";
                                                if (d(Z[aF].backgroundColor) + "" == n.rgbRND + "") {
                                                    Z["cPM" + aP].color = aQ;
                                                    Z[aF].backgroundColor = (K(d(aQ)) > 129) ? "#333" : "#CCC";
                                                    Z.timeOut = setTimeout(function () {
                                                        Z[aF].backgroundColor = Z["cPM" + aP].color;
                                                        Z.timeOut = null
                                                    }, 200);
                                                    return false
                                                }
                                            }
                                            if (!Z.timeOut) {
                                                for (aP = 9; aP > 1; aP--) {
                                                    aF = "cPM" + aP + "s";
                                                    Z[aF].backgroundColor = Z["cPM" + (aP - 1) + "s"].backgroundColor;
                                                    l("cP." + aF, Z[aF].backgroundColor.replace(/,/g, "|"))
                                                }
                                                Z.cPM1s.backgroundColor = aQ;
                                                l("cP.cPM1s", aQ.replace(/,/g, "|"))
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if (aJ.name && aJ.name.search(/[CMYKX]/) == -1) {
                var aF = aJ.name,
                    aG = aF.search(/[RGB]/) != -1 ? 255 : aF.search(/[SV]/) != -1 ? 100 : 360,
                    aI = aF.search(/[RH]/) != -1 ? 0 : aF.search(/[GS]/) != -1 ? 1 : 2,
                    aV = true,
                    aO = (aG == 255) ? n.rgb[aI] : n.hsv[aI] / 255 * aG;
                aJ.className = "cPinpDrag";
                Z.inp = aJ;
                aR = aG - p(aT, [0, 0, 0, 0])[1];
                h(V, "mousemove", function (aZ) {
                    var aY = (!aV ? aO : 0) + aG - p(aZ, [0, 0, 0, 0])[1] - aR;
                    if (!aV) {
                        P(aY, aG, aI, n.rgb, n.hsv)
                    } else {
                        if (Math.abs(aY) > 10) {
                            aV = false;
                            aR += aY;
                            aJ.className = "cPinpDragOn";
                            F();
                            if (q) {
                                b = setInterval(function () {
                                    j(true, true)
                                }, q)
                            } else {
                                b = true
                            }
                            return false
                        }
                    }
                })
            } else {
                if (aJ.name) {} else {
                    return false
                }
            }
        };
        h(V, "mouseup", ab);
        Z.onkeydown = function (aP) {
            var aP = aP || window.event,
                aN = aP.target || aP.srcElement,
                aN = aN.nodeType == 3 ? aN.parentNode : aN,
                aG = aP.which || aP.keyCode,
                aG = aG >= 96 && aG <= 105 ? aG - 48 : aG,
                aL = String.fromCharCode(aG),
                aJ = {
                    37: 1,
                    38: 1,
                    39: 1,
                    40: 1,
                    46: 1,
                    8: 1,
                    9: 1,
                    13: 1,
                    33: 1,
                    34: 1
                }[aG],
                aT = aN.name,
                aS = /[RGB]/.exec(aT) ? 255 : /[SV]/.exec(aT) ? 100 : /[H]/.exec(aT) ? 360 : 16777215,
                aQ = aS > 360 ? /[0-9a-fA-F]/ : /\d/,
                aI, aH, aK, aR, aF, aO = /38|40|33|34/.exec(aG),
                aM = /8|46/.exec(aG) || aO;
            if (aG == 13) {
                if (aN == Z.cPIX) {
                    M(d(aN.value))
                }
                F();
                return false
            }
            if ((aJ && !aM) || (aN == Z.cPIX && (aJ || aQ.test(aL)))) {
                return true
            }
            if (!aT || aT.search(/[RGBHSVX]/) == -1 || (!aJ && !aQ.test(aL))) {
                return false
            }
            if (aN.value == "0") {
                aN.value = ""
            }
            if (document.selection) {
                aI = document.selection.createRange().getBookmark();
                aH = aN.createTextRange();
                aK = aN.createTextRange();
                aH.moveToBookmark(aI);
                aK.collapse(true);
                aK.setEndPoint("EndToStart", aH);
                aN.selectionStart = aK.text.length;
                aN.selectionEnd = aK.text.length + aH.text.length
            }
            aR = aN.selectionStart - (aG == 8 ? 1 : 0);
            aF = (aN.value.substr(0, aR) + (aJ ? "" : aL) + aN.value.substr(aN.selectionEnd + (aG == 46 ? 1 : 0))).replace(/^0*/g, "");
            if (aO) {
                aF = +aN.value + (aG == 38 ? 1 : aG == 40 ? -1 : aG == 33 ? (aF > (aS - 10) ? aS - aF : 10) : -10)
            }
            aN.value = aF;
            if (+aF <= aS) {
                P(+aF, aS, aT.search(/[RH]/) != -1 ? 0 : aT.search(/[GS]/) != -1 ? 1 : 2, n.rgb, n.hsv)
            }
            if (q) {
                j(true, true)
            }
            aR = aR - (aM ? 1 : 0);
            if (aN.createTextRange) {
                aI = aN.createTextRange();
                aI.move("character", aR + 1);
                aI.select()
            } else {
                aN.setSelectionRange(aR + 1, aR + 1)
            }
            return false
        };
        Z.onkeypress = function (aI) {
            var aI = aI || window.event,
                aH = aI.target || aI.srcElement,
                aG = aI.which || aI.keyCode,
                aF = {
                    37: 1,
                    38: 1,
                    39: 1,
                    40: 1,
                    46: 1,
                    8: 1,
                    9: 1,
                    13: 1
                }[aG];
            if ((aF && aH != Z.cPIX && aG != 46 && aG != 8) || (aH == Z.cPIX && (aF || /[0-9a-fA-F]/.test(String.fromCharCode(aG))))) {
                if (aG == 13 && aH == Z.cPIX) {
                    M(d(aH.value));
                    F()
                }
                return true
            } else {
                return false
            }
        };
        Z.onmouseup = Z.cPPanel.onmouseout = function (aG) {
            var aG = aG || window.event,
                aF = aG.target || aG.srcElement;
            if (!aF.className.search(/cPC*B(\d|RX)/) && !b) {
                E(aF, true)
            }
            return false
        };
        Z.ondblclick = function (aG) {
            var aG = aG || window.event,
                aF = aG.target || aG.srcElement,
                aH = /cPB(.)(.)\s+b/.exec(aF.className);
            if (aH && aH[2] != "X" && aH[1] != "R" && D > 1) {
                n.mode = aH[2] == "H" ? "R" : aH[2] == "S" ? "G" : aH[2] == "V" ? "B" : aH[2] == "R" ? "H" : aH[2] == "G" ? "S" : "V";
                M(n.rgb);
                A(" S")
            } else {
                if (aF == Z.cPCB1) {
                    if (n.bd) {
                        document.body.style.cssText = n.bd;
                        n.bd = null
                    } else {
                        n.bd = document.body.style.cssText + ";";
                        document.body.style.background = "rgb(" + n.rgbRND + ")"
                    }
                }
            }
        };
        colorPicker.importRGB = function (aF) {
            var aH, aG = colorPicker.CP;
            if (aF[0] === false) {
                aF[0] = aG.rgb[0]
            } else {
                aH = 0
            }
            if (aF[1] === false) {
                aF[1] = aG.rgb[1]
            } else {
                aH = 1
            }
            if (aF[2] === false) {
                aF[2] = aG.rgb[2]
            } else {
                aH = 2
            }
            P(aF[aH], 255, aH, aF, aG.hsv);
            if (!q) {
                j(true, true)
            } else {
                if (!b) {
                    b = setInterval(function () {
                        j(true, true)
                    }, q)
                }
            }
        };
        colorPicker.importHSB = function (aG) {
            var aI, aF, aH = colorPicker.CP;
            if (aG[0] === false) {
                aG[0] = aH.hsv[0]
            } else {
                aI = 0;
                aF = 360
            }
            if (aG[1] === false) {
                aG[1] = aH.hsv[1]
            } else {
                aI = 1;
                aF = 100
            }
            if (aG[2] === false) {
                aG[2] = aH.hsv[2]
            } else {
                aI = 2;
                aF = 100
            }
            P(aG[aI], aF, aI, aH.rgb, aG);
            if (!q) {
                j(true, true)
            } else {
                if (!b) {
                    b = setInterval(function () {
                        j(true, true)
                    }, q)
                }
            }
        };
        colorPicker.importColor = function (aF) {
            M(d(aF))
        };
        colorPicker.stopRendering = function () {
            clearInterval(b);
            b = false;
            j(true, true)
        };
        ah = aq = x = ar = at = aB = az = e = aE = al = ax = y = aD = ai = ak = an = aA = aC = aj = ap = am = null;
        n = colorPicker.CP = {};
        n.modeRGB = {};
        n.mode = colorPicker.mode || i;
        colorPicker.rSpeed = q
    } : colorPicker.CP, M = function (y) {
        G = /R|G|B/.exec(n.mode) ? "RGB" : "HSV";
        R = /R|G/.exec(n.mode) ? 2 : n.mode == "H" ? 1 : 0;
        O = /S|H/.exec(n.mode) ? 2 : n.mode == "G" ? 0 : 1;
        N = /R|H/.exec(n.mode) ? 0 : /G|S/.exec(n.mode) ? 1 : 2;
        l("CP.mode", n.mode);
        if (!J) {
            Z.cPResizes.display = "none"
        }
        if (!aa) {
            Z.cPCloses.display = "none"
        }
        D = /cPSkin(\s+S)*(\s+XS)*(\s+XXS)*/.exec(Z.className);
        B = D[3] ? 2 : 1;
        D = D[1] ? 2 : 1;
        H = (D > 1 ? 128 : 0) + (B > 1 ? 64 : 0);
        for (var z = 0; z < Z.but.length; z++) {
            if (Z.but[z][1] == "L") {
                E(Z[Z.but[z][0]], Z.but[z][2] != n.mode)
            }
        }
        for (var z = 0, x = "RGB".split(""); z < x.length; z++) {
            if (n.modeRGB[x[z]]) {
                Z["cPBR" + x[z]].className = "cPBR" + x[z] + " bUp"
            }
        }
        u = T(Z.cPCTRT.parentNode, "width").replace("px", "") - W;
        n.iCtr = K(n.CB2Color);
        Y = null;
        ac = null;
        Z.cPCB2s.backgroundColor = "rgb(" + n.CB2Color + ")";
        q = colorPicker.rSpeed;
        for (var z = 1, e = "L"; z <= 3; z++) {
            Z["cPS" + e + z].className = "cPS" + e + z + n.mode + (n.modeRGB[n.mode] && z > 1 && e == "R" ? " cPhide" : "");
            if (z > 2 && e == "L") {
                e = "R";
                z = 0
            }
        }
        n.rgb = [];
        n.hsv = [];
        if (G == "HSV") {
            y = af(y[0], y[1], y[2])
        }
        k(null, null, true, [y[R], y[O], y[N]], true)
    }, k = function (ai, al, am, ah, x, ak, z) {
        var y = colorPicker.CP,
            aj;
        if (!ah) {
            aj = p(ai, al);
            aj[1] = aj[1] < 0 ? 255 : aj[1] * D * B > 255 ? 0 : 255 - aj[1] * D * B;
            if (am) {
                y.xyz[0] = aj[0] < 0 ? 0 : aj[0] * D > 255 ? 255 : aj[0] * D;
                y.xyz[1] = aj[1]
            } else {
                y.xyz[2] = aj[1]
            }
        } else {
            y.xyz = ah
        }
        if (G == "RGB") {
            if (ak) {
                y.rgb = ak
            } else {
                y.rgb[R] = y.xyz[0];
                y.rgb[O] = y.xyz[1];
                y.rgb[N] = y.xyz[2]
            }
            y.hsv = z ? z : af(y.rgb[0], y.rgb[1], y.rgb[2])
        } else {
            if (z) {
                y.hsv = z
            } else {
                y.hsv[R] = y.xyz[0];
                y.hsv[O] = y.xyz[1];
                y.hsv[N] = y.xyz[2]
            }
            y.rgb = ak ? ak : I(y.hsv[0], y.hsv[1], y.hsv[2], true)
        }
        y.rgbRND = [Math.round(y.rgb[0]), Math.round(y.rgb[1]), Math.round(y.rgb[2])];
        y.cmyk = L(y.rgb[0], y.rgb[1], y.rgb[2]);
        y.cmyk = [Math.round(y.cmyk[0] * 100), Math.round(y.cmyk[1] * 100), Math.round(y.cmyk[2] * 100), Math.round((1 - y.cmyk[3]) * 100)];
        y.hex = w(y.rgbRND[0], y.rgbRND[1], y.rgbRND[2]);
        if (!q || x) {
            j(am, ah)
        }
    }, j = function (aq, aj) {
        var y = colorPicker.CP,
            e = colorPicker.cP,
            an = 0,
            al = 0,
            ak = 0,
            ah, z, ao, ai, ap, am, x;
        if (aq) {
            if (y.xyz[0] > y.xyz[1]) {
                al = 1
            } else {
                an = 1
            }
            if (y.mode == "S" || y.mode == "V") {
                e.cPSR2s.backgroundColor = "rgb(" + I(y.xyz[0], 255, 255) + ")";
                al = 1;
                ak = 255
            } else {
                if (y.mode != "H" && !y.modeRGB[y.mode]) {
                    e.cPSR2.className = "cPSR" + (2 + an) + y.mode + " cPOP" + Math.round((y.xyz[an] - y.xyz[al]) / (255 - y.xyz[al]) * 100 || 0)
                }
            }
            if (y.mode != "H" && !y.modeRGB[y.mode]) {
                e.cPSR3.className = "cPSR4" + y.mode + " cPOP" + Math.round(Math.abs(ak - y.xyz[al]) / 2.55)
            }
            e.cPSLCs.cssText = "left:" + (y.xyz[0] / D - 5) + "px;top:" + Math.ceil(250 - y.xyz[1] / D / B - H) + "px"
        }
        if (!aq || aj) {
            if (y.mode == "H") {
                ao = I(y.xyz[2], 255, 255);
                e.cPSL1s.backgroundColor = "rgb(" + ao + ")"
            } else {
                e.cPSL3.className = "cPSL3" + y.mode + " cPOP" + (100 - Math.round(y.xyz[2] / 2.55))
            }
            e.cPSRCLs.top = ak = +Math.ceil(252 - y.xyz[2] / D / B - H) + "px";
            if (aj) {
                e.cPSRCRs.top = ak
            }
        }
        am = K(y.rgbRND);
        ai = am > 128;
        if (Y !== ai) {
            if (ai) {
                if (o) {
                    e.cObjs.color = "#222"
                }
                e.cPSLC.className = "cPSLCB";
                y.cPM0CN = "cPM0 cPM0B";
                if (y.mode != "H" && !y.modeRGB[y.mode]) {
                    e.cPSRCL.className = "cPSRCLB";
                    if (aq) {
                        e.cPSRCR.className = "cPSRCRB"
                    }
                }
            } else {
                if (o) {
                    e.cObjs.color = "#ddd"
                }
                e.cPSLC.className = "cPSLCW";
                y.cPM0CN = "cPM0";
                if (y.mode != "H" && !y.modeRGB[y.mode]) {
                    e.cPSRCL.className = "cPSRCLW";
                    if (aq) {
                        e.cPSRCR.className = "cPSRCRW"
                    }
                }
            }
        }
        Y = ai;
        if (!aq || aj) {
            if (y.modeRGB[y.mode]) {
                ap = y.xyz[2] > 153;
                if (ac !== ap) {
                    if (ap && y.mode == "G") {
                        e.cPSRCL.className = "cPSRCLB";
                        if (aq) {
                            e.cPSRCR.className = "cPSRCRB"
                        }
                    } else {
                        e.cPSRCL.className = "cPSRCLW";
                        if (aq) {
                            e.cPSRCR.className = "cPSRCRW"
                        }
                    }
                }
            } else {
                if (y.mode == "H") {
                    ap = K(ao || I(y.hsv[0], 255, 255)) > 128;
                    if (ac !== ap) {
                        if (ap) {
                            e.cPSRCL.className = "cPSRCLB"
                        } else {
                            e.cPSRCL.className = "cPSRCLW"
                        }
                    }
                }
            }
            ac = ap
        }
        z = X(y.CB2Color, y.rgb) / 765 * u;
        ah = Math.abs((am - y.iCtr) / 255 * u);
        e.cPCTRTs.cssText = "width:" + ah + "px;" + ((z > ah) ? e.CTRTop : "");
        e.cPCDs.cssText = "width:" + z + "px;" + ((ah < u / 2 && z < u / 3 * 2) ? "" : ((ah < u / 2 || z < u / 3 * 2) ? e.cPCD1 : e.cPCD2));
        e.cPIR.value = y.rgbRND[0];
        e.cPIG.value = y.rgbRND[1];
        e.cPIB.value = y.rgbRND[2];
        e.cPIH.value = Math.round(y.hsv[0] / 255 * 360);
        e.cPIS.value = Math.round(y.hsv[1] / 2.55);
        e.cPIV.value = Math.round(y.hsv[2] / 2.55);
        e.cPIC.value = y.cmyk[0];
        e.cPIM.value = y.cmyk[1];
        e.cPIY.value = y.cmyk[2];
        e.cPIK.value = y.cmyk[3];
        e.cPIX.value = y.hex;
        x = (y.rgbRND[0] % 51 == 0 && y.rgbRND[1] % 51 == 0 && y.rgbRND[2] % 51 == 0) ? "W" : (y.rgbRND[0] % 17 == 0 && y.rgbRND[1] % 17 == 0 && y.rgbRND[2] % 17 == 0) ? "M" : "!";
        if (x != y.WS) {
            e.cPBRX.firstChild.data = y.WS = x
        }
        e.cPCB1s.backgroundColor = "rgb(" + y.rgbRND + ")";
        if (o) {
            e.cObjs.backgroundColor = "rgb(" + y.rgbRND + ")"
        }
        if (v) {
            e.cObj.value = y.valPrefix + y.hex
        }
        if (y.bd) {
            document.body.style.background = "rgb(" + y.rgbRND + ")"
        }
        if (colorPicker.exportColor) {
            colorPicker.exportColor()
        }
        if (aq && aj) {
            e.cPSRCR.className = e.cPSRCL.className.replace("L", "R");
            e.cPM0s.backgroundColor = "rgb(" + y.rgbRND + ")";
            if (y.cPM0CN) {
                e.cPM0.className = y.cPM0CN;
                y.cPM0CN = ""
            }
        }
    }, ab = function () {
        a(V, "mousemove");
        if (b) {
            clearInterval(b);
            b = false;
            j(true, true)
        }
        Z.cPSL4.className = "cPSL4";
        Z.cPResizers.cssText = "";
        Z.cObj.osX = Z.style.left;
        Z.cObj.osY = Z.style.top;
        if (Z.inp) {
            Z.inp.className = ""
        }
    }, P = function (z, y, ah, x, e) {
        if (Z.WEBS1) {
            Z.WEBS1 = null
        }
        z = (z < 0) ? 0 : (z > y) ? y : z;
        if (y == 255) {
            x[ah] = z;
            if (G == "HSV") {
                e = af(x[0], x[1], x[2])
            }
        } else {
            e[ah] = z / y * 255;
            if (G == "RGB") {
                x = I(e[0], e[1], e[2])
            }
        }
        if (G == "RGB") {
            k(null, null, q == 0, [x[R], x[O], x[N]], false, x, (y != 255) ? e : false)
        } else {
            k(null, null, q == 0, [e[R], e[O], e[N]], false, (y == 255) ? x : false, e)
        }
    }, E = function (x, e) {
        x.className = (e) ? x.className.replace("bDown", "bUp") : x.className.replace("bUp", "bDown")
    }, A = function (e) {
        Z.className = "cPSkin" + e;
        D = (e) ? 2 : 1;
        B = (e == " S XS XXS") ? 2 : 1;
        H = (D > 1 ? 128 : 0) + (B > 1 ? 64 : 0);
        if (e == " S XS XXS" && (G == "RGB" || n.mode == "H")) {
            n.modeTmp = n.mode;
            n.mode = "S";
            M(n.rgb)
        } else {
            if (n.modeTmp && n.modeTmp != n.mode) {
                n.mode = n.modeTmp;
                n.modeTmp = null;
                M(n.rgb)
            } else {
                j(true, true)
            }
        }
        Z.cPRGB.className = (G == "RGB" || !e) ? "cPRGB" : "cPhide";
        Z.cPHSB.className = (G == "HSV" || !e) ? "cPHSB" : "cPhide";
        l("size", e == " S XS XXS" ? 1 : e == " S XS" ? 2 : e == " S" ? 3 : 4)
    }, ad = function (e, x) {
        var y;
        if (e && !ag) {
            Z.cPSkins.display = "none"
        } else {
            if (!ag) {
                if (Z.cObj && x.parentNode != Z.cObj.parentNode) {
                    x.parentNode.appendChild(Z.parentNode.removeChild(Z))
                }
                y = Z.parentNode.style;
                if (T(Z.parentNode, "position") == "static") {
                    y.position = "relative"
                }
                if (!/(display|height|width|zoom)/.exec(y.cssText.toLowerCase())) {
                    y.zoom = "1"
                }
            }
            if (ag) {
                Z.cPSkins.cssText = Z.cPSkins.cssText.replace(m, "") + m
            } else {
                Z.cPSkins.position = "absolute";
                Z.cPSkins.display = "";
                Z.cPSkins.left = (x.osX ? x.osX : (x.offsetLeft + S + (t[1] == "right" ? x.offsetWidth : 0)) + "px");
                Z.cPSkins.top = (x.osY ? x.osY : (x.offsetTop - (t[0] == "top" ? Z.offsetHeight - (t[1] == "right" ? x.offsetHeight : -Q) : t[1] == "right" ? x.offsetHeight - x.offsetHeight : -Q - x.offsetHeight)) + "px")
            }
            Z.cObj = x;
            M(n.CB2Color)
        }
    }, F = function () {
        Z.cPdummy.focus();
        if (s) {
            Z.cPdummy.blur()
        }
    }, K = function (e) {
        return Math.sqrt(e[0] * e[0] * 0.241 + e[1] * e[1] * 0.691 + e[2] * e[2] * 0.068)
    }, X = function (x, e) {
        return (Math.max(x[0], e[0]) - Math.min(x[0], e[0])) + (Math.max(x[1], e[1]) - Math.min(x[1], e[1])) + (Math.max(x[2], e[2]) - Math.min(x[2], e[2]))
    }, d = function (e) {
        e = (e + "").replace(/[(^rgb\()]*[^a-fA-F0-9,]*/g, "").split(",");
        if (e.length == 3) {
            return [+e[0], +e[1], +e[2]]
        }
        e += "";
        if (e.length == 3) {
            e = e.split("");
            return [parseInt((e[0] + e[0]), 16), parseInt((e[1] + e[1]), 16), parseInt((e[2] + e[2]), 16)]
        }
        while (e.length < 6) {
            e = "0" + e
        }
        return [parseInt(e.substr(0, 2), 16), parseInt(e.substr(2, 2), 16), parseInt(e.substr(4, 2), 16)]
    }, w = function (y, x, e) {
        return ((y < 16 ? "0" : "") + y.toString(16) + (x < 16 ? "0" : "") + x.toString(16) + (e < 16 ? "0" : "") + e.toString(16)).toUpperCase()
    }, I = function (ap, an, am, aq) {
        var e = 0,
            aj = 0,
            ao = 0,
            al = 0,
            ak = (100 - an / 2.55) / 100,
            ai = am / 255,
            ah = am * (255 - an) / 255;
        if (ap < 42.5) {
            e = am;
            aj = ap * 6 * ai;
            aj += (am - aj) * ak;
            ao = ah
        } else {
            if (ap >= 42.5 && ap < 85) {
                al = 42.5;
                e = (255 - (ap - al) * 6) * ai;
                e += (am - e) * ak;
                aj = am;
                ao = ah
            } else {
                if (ap >= 85 && ap < 127.5) {
                    al = 85;
                    e = ah;
                    aj = am;
                    ao = (ap - al) * 6 * ai;
                    ao += (am - ao) * ak
                } else {
                    if (ap >= 127.5 && ap < 170) {
                        al = 127.5;
                        e = ah;
                        aj = (255 - (ap - al) * 6) * ai;
                        aj += (am - aj) * ak;
                        ao = am
                    } else {
                        if (ap >= 170 && ap < 212.5) {
                            al = 170;
                            e = (ap - al) * 6 * ai;
                            e += (am - e) * ak;
                            aj = ah;
                            ao = am
                        } else {
                            if (ap >= 212.5) {
                                al = 212.5;
                                e = am;
                                aj = ah;
                                ao = (255 - (ap - al) * 6) * ai;
                                ao += (am - ao) * ak
                            }
                        }
                    }
                }
            }
        }
        if (aq) {
            return [e, aj, ao]
        } else {
            return [Math.round(e), Math.round(aj), Math.round(ao)]
        }
    }, af = function (ai, ah, x) {
        var aj = Math.min(Math.min(ai, ah), x),
            y = Math.max(Math.max(ai, ah), x),
            e = y - aj,
            z = 0;
        if (e === 0) {
            return [0, 0, y]
        }
        z = ai === aj ? 3 + (x - ah) / e : (ah === aj ? 5 + (ai - x) / e : 1 + (ah - ai) / e);
        return [z === 6 ? 0 : z * 42.5, e / y * 255, y]
    }, L = function (ah, z, e) {
        var y = Math.min(1 - ah, 1 - z, 1 - e),
            x = 1 - y;
        if (y == 1) {
            return [0, 0, 0, -y / 255]
        } else {
            return [(1 - ah - y) / x, (1 - z - y) / x, (1 - e - y) / x, -y / 255]
        }
    }, T = function (e, x) {
        if (e.currentStyle) {
            return e.currentStyle[x]
        } else {
            if (window.getComputedStyle) {
                return document.defaultView.getComputedStyle(e, null).getPropertyValue(x)
            }
        }
    }, r = function (ah) {
        var x = null,
            y = null,
            ai = [],
            e = document.body.scrollLeft + document.documentElement.scrollLeft,
            z = document.body.scrollTop + document.documentElement.scrollTop;
        if (ah.parentNode === null || T(ah, "display") == "none") {
            return false
        }
        if (ah.getBoundingClientRect) {
            y = ah.getBoundingClientRect();
            return [Math.round(y.left) + (document.documentElement.scrollLeft || document.body.scrollLeft), Math.round(y.top) + (document.documentElement.scrollTop || document.body.scrollTop), e, z]
        } else {
            if (document.getBoxObjectFor) {
                y = document.getBoxObjectFor(ah);
                ai = [y.x, y.y]
            } else {
                ai = [ah.offsetLeft, ah.offsetTop];
                x = ah.offsetParent;
                if (x != ah) {
                    while (x) {
                        ai[0] += x.offsetLeft;
                        ai[1] += x.offsetTop;
                        x = x.offsetParent
                    }
                }
                if (window.opera || (document.childNodes && !document.all && !navigator.taintEnabled && !accentColorName)) {
                    ai[1] -= document.body.offsetTop
                }
            }
        }
        if (ah.parentNode) {
            x = ah.parentNode
        } else {
            x = null
        }
        while (x && x.tagName != "BODY" && x.tagName != "HTML") {
            ai[0] -= x.scrollLeft;
            ai[1] -= x.scrollTop;
            if (x.parentNode) {
                x = x.parentNode
            } else {
                x = null
            }
        }
        return ai.concat([e, z])
    }, p = function (y, x) {
        p = (typeof y.pageX === "number" && !window.opera) ?
        function (ah, z) {
            return [ah.pageX - z[0], ah.pageY - z[1]]
        } : function (ah, z) {
            return [ah.clientX + z[2] - z[0], ah.clientY + z[3] - z[1]]
        };
        return p(y, x)
    }, h = function (y, e, x) {
        if (!y || !e || !x) {
            return false
        }
        y.funcCache = y.funcCache || {};
        y.funcCache[e] = x;
        if (y.addEventListener) {
            y.addEventListener(e, x, false)
        } else {
            y.attachEvent("on" + e, x)
        }
    }, a = function (y, e, x) {
        if (!y || !e) {
            return false
        }
        if (!x && (!y.funcCache || !y.funcCache[e])) {
            return false
        }
        if (y.removeEventListener) {
            y.removeEventListener(e, x || y.funcCache[e], false)
        } else {
            y.detachEvent("on" + e, x || y.funcCache[e])
        }
    }, l = function (y, z) {
        var x = new Date(),
            e = "";
        if (c) {
            e = "; expires=" + (new Date(x.getTime() + c * 60 * 60 * 24 * 1000).toGMTString())
        }
        if (typeof z != undefined) {
            document.cookie = y + "=" + (z.toString().replace(/\s+/g, " ")) + e + "; path=/"
        }
    };
    (function () {
        var x = ag || ae.target || ae.srcElement,
            e = document.cookie.split(";"),
            z, y;
        if (g) {
            x.blur()
        }
        if (!Z) {
            n(x);
            ae = false
        }
        for (z = 0; z < e.length; z++) {
            y = e[z];
            while (y.charAt(0) == " ") {
                y = y.substring(1, y.length)
            }
            if (y = /(^cP\.|^CP\.|^size)(.*)=(.*)/.exec(y)) {
                if (y[3]) {
                    if (y[1] == "cP.") {
                        Z[y[2]].backgroundColor = y[3].replace(/\|/g, ",")
                    } else {
                        if (y[1] == "CP.") {
                            y[2] = y[2].split(".");
                            if (y[2][1]) {
                                n[y[2][0]][y[2][1]] = y[3] == "false" ? false : true
                            } else {
                                n[y[2]] = y[3]
                            }
                        } else {
                            f = y[3]
                        }
                    }
                }
            }
        }
        n.CB2Color = x.color = d(x.value || C || [204, 0, 0]);
        if (Z.WEBS1) {
            Z.WEBS1 = null
        }
        Z.cObjs = x.style;
        if (x.value) {
            n.valPrefix = /(#*)/.exec(x.value)[0]
        } else {
            n.valPrefix = "#"
        }
        ad(Z.cPSkins.display == "" && x == Z.cObj ? true : false, x);
        if (!ae) {
            A(f == 1 ? " S XS XXS" : f == 2 ? " S XS" : f == 3 ? " S" : "")
        }
    })()
};
