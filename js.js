async function pagar(d){
    //console.info(d);
    if(!MP_DEVICE_SESSION_ID){MP_DEVICE_SESSION_ID = '';}
    
    let enlace = await fetch('mp.php', {
        method: 'POST',
        body: JSON.stringify({
            data: d,
            dId: MP_DEVICE_SESSION_ID
        })
    })

    if(enlace.status === 202){
        location.href = await enlace.text();
    }else{
        console.group();
            console.info(enlace);
            console.info(await enlace.text());
        console.groupEnd();
    }
}
