@extends('admin.site.layout')

@section('container')
    <div id="admin-site-comment" class="site-contribution">
        <div class="section">
            <div class="item">
                <div class="name">
                    <h3>开启评论</h3>
                </div>
                <div class="slider" v-bind:class="{'true': open == 'true'}" v-on:click="_slide('open')">
                            <span>
                                <em></em>
                            </span>
                </div>
            </div>
            <div class="item">
                <div class="name">
                    <h3>开启外部评论</h3>
                </div>
                <div class="slider" v-bind:class="{'true': ex == 'true'}" v-on:click="_slide('ex')">
                            <span>
                                <em></em>
                            </span>
                </div>
            </div>
        </div>
        <div class="btn-group">
            <a class="save pub-background-transition" v-bind:class="save" v-on:click="_save"><em></em><span>保存</span></a>
        </div>
    </div>
@stop

@section('script-site')@parent<script>
    (function () {
        this.comment = {
            open : '{{$info->comment ? 'true' : 'false'}}',
            ex :  '{{$info->comment_ex ? 'true' : 'false'}}'
        }
    }).call(define('data'));
</script>
@stop