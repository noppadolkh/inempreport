<div>
    
    @foreach($empcometoday as $emp)
        
        <h4>
            {{$emp->name.' ลงชื่อ'.($emp->arrived == 'true' ? 'มาทำงาน' : 'ไม่มาทำงาน')}}

            <font style="color:{{ date('H:i:s',strtotime($emp->arrive_time)) > date('09:00:00') == 'true' ? 'red' : 'blue' }}">{{' เวลา: '.$emp->arrive_time}}</font>
            @if($emp->normal_time != 'true')
                <h4>{{'(มีการแก้ไขเวลาเข้างาน)'}}</h4>
            @endif
        </h4>
        
        
        
        <br>
    @endforeach
</div>