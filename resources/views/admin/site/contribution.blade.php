@extends('admin.site.layout')

@section('container')
    <div id="admin-site-contribution" class="site-contribution">
        <div class="section">
            <div class="item">
                <div class="name">
                    <h3>开启外部投稿</h3>
                </div>
                <div class="slider" v-bind:class="{'true': contribute == '1'}" v-on:click="_slide('contribute')">
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
        this.contribution = {
            contribute : '{{$info->contribute}}'
        }
    }).call(define('data'));
</script>
@stop