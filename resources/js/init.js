export default {
    methods:{
        initDoc(){
          
            $(document).ready(function() {

                function setSelectionRange(input, selectionStart, selectionEnd) {
                    if (input.setSelectionRange) {
                        input.focus();
                        input.setSelectionRange(selectionStart, selectionEnd);
                    } else if (input.createTextRange) {
                        var range = input.createTextRange();
                        range.collapse(true);
                       
                        range.moveEnd('character', selectionEnd);
                        range.moveStart('character', selectionStart);       
                        range.select();
                    }
                }

                function setCaretToPos(input, pos) {
                    setSelectionRange(input, pos, pos);
                }
                
                $(".price").click(function() {
                    var field = $(this);
                    var inputLength = field.val().length;
                    setCaretToPos(field[0], inputLength);
                });

                var options = {
                    onKeyPress: function(cep, e, field, options){
                        if (cep.length<=6)
                        {
                        
                            var inputVal = parseFloat(cep);
                            
                            $(e).val(inputVal.toFixed(2));
                        }
                                
                        var masks = ['###0.00', '0.00'];
                        mask = (cep == 0) ? masks[1] : masks[0];
                    },
                    reverse: true
                };

                $('.price').mask('###0.00', options);

            });
        }
    }
}