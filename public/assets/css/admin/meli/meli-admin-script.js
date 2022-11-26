function meliAdminMenuBurger(target){
    var position = $(target).css('transform')
    if(position === "matrix(1, 0, 0, 1, 0, 0)"){
        // slide bruger menu out the screen
        $(target+' a').css('display','none')
        $(target).css('transform','translateX(-100%)')
        $(target).css('display','none')
        // increase container's width
        $('.meli-admin-container').css('width','100%')
    }else{
        $(target).css('display','block')
        $(target).css('transform','translateX(0)')
        // decrease container's 
        $('.meli-admin-container').css('width','85%')
        $(target+' a').css('display', 'inline')
    }

}



function meliConfirmDelete(url){
    if(confirm('Voulez-vous supprimer cet élément (définitivement) ?')){
        $(location).attr('href',url)
    }
}