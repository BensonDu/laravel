@extends('platform.admin.layout')
@section('style')<link href="{{ $_ENV['platform']['cdn'].elixir("css/platform.admin.site.css") }}" rel="stylesheet">
@stop
@section('area')
    <div class="list-header">
        <div class="nav">
            <a class="active" href="/admin/site">全部站点</a>
        </div>
        <div class="nav-right">
            <a v-on:click="_add"><em>+</em> <span>添加</span></a>
        </div>
        <div class="divider">
            <em></em>
        </div>
        <div class="search">
            <div class="input"><em></em><input v-model="search.keyword" v-on:keyup.enter="_search" type="text" placeholder="搜索站点 "><i></i></div>
        </div>
    </div>
    <div class="list-body">
        <table>
            <thead>
            <tr>
                <th><span>站点名称</span></th>
                <th><span>允许访问</span><em></em></th>
                <th class="orderable" v-bind:class="order" v-on:click="_order()"><span>添加时间</span><em></em></th>
                <th><span>操作</span></th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="s in list">
                <td class="clickable active"><a target="_blank" v-bind:href="s.home+'admin/site'" v-text="s.name"></a></td>
                <td><p class="slider" v-bind:class="s.valid == 1 && 'true'" v-on:click="_open($index)"><span><em></em></span></p></td>
                <td v-text="s.time"></td>
                <td class="handle">
                    <div>
                        <a class="edit" target="_blank" v-bind:href="s.home+'admin/site'"><em></em><span>修改</span></a>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="list-footer">
        <div class="page-size">
            <p>每页显示</p>
        <span>
            <a v-on:click="_size(10)" v-bind:class="pagination.size == 10 ? 'active' : ''">10</a>
            <a v-on:click="_size(20)" v-bind:class="pagination.size == 20 ? 'active' : ''">20</a>
            <a v-on:click="_size(50)" v-bind:class="pagination.size == 50 ? 'active' : ''">50</a>
        </span>
            <p>条</p>
        </div>
        <div class="page-info">
            <p>共 <span v-text="pagination.total"></span> 条</p>
        </div>
        <div class="pagination">
            <a class="btn" v-on:click="_prev()"v-bind:class="pagination.index != 1 ? 'active' : ''" ><em class="prev"></em></a>
        <span>
            <a v-for="b in pagination.btns" v-bind:class="{ 'disable': b.index == '...', 'active': b.active }" v-text="b.index" v-on:click="_turn(b.index)"></a>
        </span>
            <a class="btn" v-on:click="_next()" v-bind:class="pagination.index+1<=pagination.all ? 'active' : ''"><em class="next"></em></a>
        </div>
    </div>
@stop
@section('pop')
    <div id="pop-container" class="pop-container" v-bind:class="display">
        <div class="thin-box add">
            <div class="header">
                <h3><em></em></h3>
                <a v-on:click="_close">×</a>
            </div>

            <div class="domain">
                <div class="swith-bar" v-bind:class="add.platform ? 'left' : 'right'" v-on:click="_switch">
                    <a>
                        <p class="left">平台域名</p>
                        <p class="right">自定义域名</p>
                    </a>
                </div>
                <div class="handle">
                    <div class="platform-domain" v-if="add.platform">
                        <div class="title"><p>平台二级域名</p></div>
                        <div class="input">
                            <input maxlength="10" placeholder="sub" v-model="add.domain"><p v-text="'.'+add.base"></p>
                        </div>
                    </div>
                    <div class="unit domain-name" v-if="add.platform">
                        <div class="title"><p>M站域名</p></div>
                        <div class="input">
                            <input class="disable" maxlength="20" placeholder="输入域名" v-model="'m.'+add.domain+'.'+add.base" disabled="disabled">
                        </div>
                    </div>
                    <div class="unit domain-name" v-if="!add.platform">
                        <div class="title"><p>自定义域名</p></div>
                        <div class="input">
                            <input maxlength="20" placeholder="输入域名" v-model="add.domain">
                        </div>
                    </div>
                    <div class="unit domain-name" v-if="!add.platform">
                        <div class="title"><p>M站自定义域名</p></div>
                        <div class="input">
                            <input class="disable" maxlength="20" placeholder="输入域名" v-model="'m.'+add.domain" disabled="disabled">
                        </div>
                    </div>
                    <div class="unit icp">
                        <div class="title"><p>ICP备案号</p></div>
                        <div class="input">
                            <input maxlength="20" placeholder="输入备案号" v-model="add.icp" v-bind:class="add.platform && 'disable'" v-bind:disabled="add.platform && 'disabled'">
                        </div>
                    </div>
                </div>
            </div>

            <div class="unit site-name">
                <div class="title"><p>站点名称</p></div>
                <div class="input">
                    <input maxlength="20" placeholder="输入站点名称" v-model="add.name">
                </div>
            </div>

            <div class="confirm">
                <div><a v-on:click="_confirm_add"><em class="yes"></em><span>确定</span></a></div>
                <div><a v-on:click="_close"><em class="no"></em><span>取消</span></a></div>
            </div>
        </div>
    </div>
@stop
@section('script')@parent<script>
    (function () {
        this.list = JSON.parse('{!! json_encode_safe($sites['list']) !!}');
        this.total= '{{$sites['total']}}';
        this.order= 'asc';
        this.icp  = '津ICP备14006995号-4';
        this.platform = {
            domain : '{{$_ENV['platform']['domain']}}'
        }
    }).call(define('data'));
</script>
<script src="{{ $_ENV['platform']['cdn'].elixir("js/platform/admin.site.js")}}"></script>
@stop