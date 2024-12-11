<style>
    .event-filter {
        list-style: none;
        padding: 0;
    }

    .event-item {
        display: flex;
        justify-content: ;
        align-items: center;
        border-radius: 5px;
    }

    .event-item input {
        margin-right: 10px;
    }

    .event-item i {
        font-size: 1.2rem;
    }
</style>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<div class="col-md-12">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Events</h5>
            <hr>

            <form id="filterForm">
                <!-- Example Event Filter Options -->
                <div class="event-filter">
                    <div class="event-item"
                        style="background-color: #e3f2fd; border-left: 5px solid #42a5f5; padding: 10px; margin-bottom: 5px;">
                        <input type="checkbox" id="personal" name="eventType" value="Personal" checked>
                        <label for="personal">Personal</label>
                        <i class="fa-solid fa-user-circle" style="position: absolute; right: 20px; color: #42a5f5;"></i>
                    </div>
                    <div class="event-item"
                        style="background-color: #fce4ec; border-left: 5px solid #ec407a; padding: 10px; margin-bottom: 5px;">
                        <input type="checkbox" id="academic" name="eventType" value="Academic" checked>
                        <label for="academic">Academic</label>
                        <i class="fa-solid fa-graduation-cap"
                            style="position: absolute; right: 20px; color: #ec407a;"></i>
                    </div>
                    <div class="event-item"
                        style="background-color: #e8f5e9; border-left: 5px solid #66bb6a; padding: 10px; margin-bottom: 5px;">
                        <input type="checkbox" id="entrepreneurship" name="eventType" value="Entrepreneurship" checked>
                        <label for="entrepreneurship">Entrepreneurship</label>
                        <i class="fa-solid fa-briefcase" style="position: absolute; right: 20px; color: #66bb6a;"></i>
                    </div>
                    <div class="event-item"
                        style="background-color: #fff8e1; border-left: 5px solid #ffb300; padding: 10px; margin-bottom: 5px;">
                        <input type="checkbox" id="sport" name="eventType" value="Sport" checked>
                        <label for="sport">Sport</label>
                        <i class="fa-solid fa-football" style="position: absolute; right: 20px; color: #ffb300;"></i>
                    </div>
                    <div class="event-item"
                        style="background-color: #f8d9c6; border-left: 5px solid #ff7043; padding: 10px; margin-bottom: 5px;">
                        <input type="checkbox" id="volunteering" name="eventType" value="Volunteering" checked>
                        <label for="volunteering">Volunteering</label>
                        <i class="fa fa-hand-holding-heart"
                            style="position: absolute; right: 20px; color: #ff7043;"></i>
                    </div>
                    <!-- Add more event items as needed -->
                </div>
            </form>
        </div>
    </div>
</div>