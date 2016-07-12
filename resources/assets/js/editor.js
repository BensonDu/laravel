/**
 * Created by Benson on 16/7/12.
 */
//编辑器配置,插入 图片|视频 插件引入
(function(){
    var self =this;

    this.content = new MediumEditor('#content-editor',{
        placeholder: {
            text: '输入文章内容'
        },
        toolbar: {
            buttons: ['bold', 'italic', 'underline','h2','h3',
                {
                    name: 'anchor',
                    action: 'createLink',
                    aria: 'link',
                    tagNames: ['a'],
                    contentDefault: '<i class="fa fa-link"></i>',
                    contentFA: '<i class="fa fa-link"></i>'
                },
                'orderedlist','unorderedlist', 'quote']
        },
        anchor: {
            customClassOption: null,
            customClassOptionText: 'Button',
            linkValidation: false,
            placeholderText: '粘贴或输入链接',
            targetCheckbox: false,
            targetCheckboxText: 'Open in new window'
        }
    });
    //拖放图片;
    self.content.subscribe('editableDrop',function (e) {
        setTimeout(function () {
            var imgs = jQuery('#content-editor').find('img');
            imgs.each(function () {
                var _this   = jQuery(this),
                    src     = _this.attr('src'),
                    grand   = _this.parent().parent(),
                    dom     = '<div class="medium-insert-images"><figure><img src="'+src+'"></figure></div>';
                if(!grand.hasClass('medium-insert-images')){
                    _this.replaceWith(dom);

                }
                else{
                    if(_this.siblings('img').length){
                        _this.replaceWith('');
                        grand.after(dom);
                    }
                }
            });
        },50);
    });
    this.insert_plugin = $('#content-editor').mediumInsert({
        editor: self.content,
        insertBtnPositionFix : {
            left : -50
        }
    });

}).call(define('plugin_editor'));