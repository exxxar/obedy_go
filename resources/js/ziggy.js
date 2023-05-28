const Ziggy = {"url":"http:\/\/127.0.0.1:8000","port":8000,"defaults":{},"routes":{"sanctum.csrf-cookie":{"uri":"sanctum\/csrf-cookie","methods":["GET","HEAD"]},"ignition.healthCheck":{"uri":"_ignition\/health-check","methods":["GET","HEAD"]},"ignition.executeSolution":{"uri":"_ignition\/execute-solution","methods":["POST"]},"ignition.updateConfig":{"uri":"_ignition\/update-config","methods":["POST"]},"main":{"uri":"\/","methods":["GET","HEAD"]},"self":{"uri":"self","methods":["GET","HEAD"]},"products":{"uri":"{foodPart}","methods":["GET","HEAD"],"wheres":{"foodPart":"standard|express|premium"}},"lotteries":{"uri":"lottery\/all","methods":["GET","HEAD"]},"lottery":{"uri":"lottery\/get\/{id}","methods":["GET","HEAD"]},"lottery.pick":{"uri":"lottery\/pick","methods":["POST"]},"delivery.range":{"uri":"order\/delivery","methods":["POST"]},"resend.code":{"uri":"order\/resend\/code","methods":["POST"]},"order":{"uri":"order","methods":["POST"]},"change.cart":{"uri":"cart\/change","methods":["POST"]},"clear.cart":{"uri":"cart\/clear","methods":["POST"]},"save.cart":{"uri":"cart\/save","methods":["POST"]},"print.report":{"uri":"cart\/print","methods":["GET","HEAD"]},"register":{"uri":"auth\/register","methods":["POST"]},"login":{"uri":"auth\/login","methods":["POST"]},"user":{"uri":"user","methods":["GET","HEAD"]},"logout":{"uri":"logout","methods":["POST"]}}};

if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
    Object.assign(Ziggy.routes, window.Ziggy.routes);
}

export { Ziggy };