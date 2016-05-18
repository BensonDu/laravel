@extends('admin.site.layout')

@section('container')
    <div class="list-body">
        <table>
            <thead>
            <tr>
                <th><span>导航名称</span></th>
                <th><span>显示状态</span></th>
                <th><span>操作</span></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="a in list">
                <td class="title active" v-on:click="_edit(a.id,a.name,a.type,a.display,a.link)"><a v-text="a.name"></a></td>
                <td class="publish" v-bind:class="a.display == '1' ? 'now' : 'cancel'"><em></em></td>
                <td class="handle">
                    <div>
                        <a class="edit" v-on:click="_edit(a.id,a.name,a.type,a.display,a.link)"><em></em><span>修改</span></a>
                        <a class="del" v-on:click="_del(a.id,a.type,a.display,a.link)" v-bind:class="a.type == 1 ? 'disable' : ''"><em></em><span>删除</span></a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="add" v-if="list.length < 6">
            <p v-on:click="_add">+</p>
        </div>
    </div>
@stop

@section('pop')
    <div id="pop-container" class="pop-container" v-bind:class="display">
        <div class="content">
            <div class="header">
                <h3><em></em><span></span></h3>
                <a v-on:click="_close">×</a>
            </div>
            <div class="section">
                <div class="title">
                    <div class="name"><p>名称</p></div>
                    <div class="textarea">
                        <input type="text" placeholder="输入导航名称" v-model="nav.name">
                    </div>
                </div>
                <div class="display">
                    <div class="name">
                        <p>是否显示</p>
                        <a class="question" v-if="nav.type == '1'">
                            <i>默认导航不可隐藏<br>无专题时, 专题导航自动隐藏</i>
                        </a>
                        <div class="slider" v-bind:class="{'true':nav.display == '1','disable' : nav.type == '1'}" v-on:click="_slide">
                            <span>
                                <em></em>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="link">
                    <div class="name">
                        <p>链接</p>
                        <a class="question" v-if="nav.type == '1'">
                            <i>默认导航链接不可更改</i>
                        </a>
                    </div>
                    <div class="textarea" v-bind:class="nav.type == '1' && 'disable'">
                        <input type="text" placeholder="输入链接" v-bind:disabled="nav.type == '1' && 'disabled'" v-model="nav.link">
                        <div class="disable"></div>
                    </div>
                </div>
            </div>
            <div class="confirm">
                <div><a><em class="yes" v-on:click="_confirm"></em><span>提交</span></a></div>
                <div><a><em class="no" v-on:click="_close"></em><span>取消</span></a></div>
            </div>
        </div>
    </div>
@stop

@section('script-site')@parent<script>
    (function () {
        this.nav = {
            list : JSON.parse('{!! json_encode_safe($list) !!}')
        }
    }).call(define('data'));
</script>
@stop