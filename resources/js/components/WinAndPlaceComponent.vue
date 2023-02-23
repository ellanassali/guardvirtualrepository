<template>
    <section class="content">
        <div class="container-fluid">
            <div class="row">

                <div class="col-12">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">RACES VALUES</h3>

                        </div>

                        <section class="content">
                            <div class="container-fluid">
                                <div class="row">

                                    <div class="col-md-9 mt-3">
                                        <div class="card">
                                            <div class="card-header p-2">
                                                <ul class="nav nav-pills">
                                                    <li class="nav-item"><a class="nav-link active" href="#activity" data-toggle="tab">Games</a></li>

                                                </ul>
                                            </div>
                                            <div class="card-body">
                                                <div class="tab-content">
                                                    <div class="active tab-pane" id="activity">
                                                        <div class="post">
                                                            <div class="user-block">


                                                            </div>
                                                            <div class="row">
                                                                <div class="col-12">
                                                                    <div class="card-body table-responsive p-0">
                                                                        <table class="table table-hover text-nowrap">
                                                                            <thead>
                                                                            <tr>
                                                                                <th>Event Date</th>
                                                                                <th>Event time (UTC)</th>
                                                                                <th>Event Id</th>
                                                                                <th>Event No</th>
                                                                                <th>Event Type</th>
                                                                                <th  style="text-align:center">Event Start (EAT)</th>
                                                                                <th  style="text-align:center">Event Finish (EAT)</th>
                                                                                <th>Player Id</th>
                                                                                <th>Position</th>
                                                                                <th>Player name</th>

                                                                                <th>Win</th>
                                                                                <th>Place</th>
                                                                                <th></th>

                                                                            </tr>
                                                                            </thead>
                                                                            <tbody>
                                                                            <!--each element of winandplace-->
                                                                            <tr v-for="(winandplace, index) in winandplacerecs" :key="winandplace.id">
                                                                                <td>{{ winandplace.event_date }}</td>
                                                                                <td>{{ winandplace.event_time }}</td>
                                                                                <td>{{ winandplace.event_id }}</td>
                                                                                <td>{{ winandplace.event_no }}</td>
                                                                                <td>{{ winandplace.event_type }}</td>
                                                                                <td style="text-align:center">{{ winandplace.local_eventTimetostart }}</td>
                                                                                <td  style="text-align:center">{{ winandplace.local_eventTimetofinish }}</td>
                                                                                <td  style="text-align:center">{{ winandplace.player_id }}</td>
                                                                                <td>{{ winandplace.draw }}</td>
                                                                                <td>{{ winandplace.name }}</td>
                                                                                <td><button :class="clickedWin[winandplace.id] ? 'bg-yellow' : 'btn  btn-secondary btn-sm'" type="button" @click="printValues(winandplace,1)" >{{ winandplace.win_odd }}</button></td>
                                                                                <td><button :class="clickedPlace[winandplace.id] ? 'bg-yellow' : 'btn  btn-secondary btn-sm'"  type="button"  @click="printValues(winandplace,2)" >{{ winandplace.place_odd }}</button></td>
                                                                                <!--Button for modal about details of each winandplace element-->
                                                                          <td>
                                                                              <b-button class="btn btn-default" @click="toggleModal(winandplace.id)">View</b-button>
                                                                          </td>
                                                                                <!--modal for details of each item-->
                                                                            <b-modal :id="winandplace.id">
                                                                                <p>Player name: {{ winandplace.name }}</p>
                                                                                <p>Positione: {{ winandplace.draw }}</p>
                                                                                <p>Event Start (EAT): {{ winandplace.local_eventTimetostart }}</p>
                                                                                <p>Event Finish (EAT): {{ winandplace.local_eventTimetofinish }}</p>
                                                                                <p>Event type: {{ winandplace.event_type }}</p>
                                                                                <p>Event no: {{ winandplace.event_no }}</p>
                                                                                <p>Event time (UTC): {{ winandplace.event_time }}</p>
                                                                                <p>Event date (UTC): {{ winandplace.event_date }}</p>

                                                                                <!--printing values if we click on buttons in modal-->
                                                                                <button :class="clickedWin[winandplace.id] ? 'bg-yellow' : 'btn  btn-secondary btn-sm'" type="button" @click="printValues(winandplace,1, 'modal', index)" >{{ winandplace.win_odd }}</button>
                                                                                <button :class="clickedPlace[winandplace.id] ? 'bg-yellow' : 'btn  btn-secondary btn-sm'"  type="button"  @click="printValues(winandplace,2,'modal', index)" >{{ winandplace.place_odd }}</button>
                                                                            </b-modal>

                                                                            </tr>

                                                                            </tbody>

                                                                        </table>
                                                                    </div>

                                                                </div>
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3 mt-3">

                                        <!-- Profile Image -->
                                        <div class="card card-primary card-outline">
                                            <div class="card-body box-profile">
                                                <div class="text-center">
                                                    <img class="profile-user-img img-fluid img-circle"
                                                         src="dist/img/user4-128x128.jpg"
                                                         alt="User profile picture">
                                                </div>

                                                <h3 class="profile-username text-center">E-sports</h3>

                                                <p class="text-muted text-center">virtuals</p>

                                                <!--data for printable localstorage and slip-->
                                                <div id="printable-values" class="d-none">
                                                    <ul v-for="(local_winandplace, index) in local_winandplaces" :key="index" class="list-group list-group-unbordered mb-3">
                                                        <li class="list-group-item">
                                                            <b>{{ local_winandplace[0].name}}</b>
                                                            <a class="float-right" v-if="1 === local_winandplace[1]">
                                                                {{ local_winandplace[0].win_odd }}
                                                            </a>
                                                            <a class="float-right" v-else-if="2 === local_winandplace[1]">{{ local_winandplace[0].place_odd }}</a>
                                                            <a v-else class="float-right"></a>
                                                        </li>
                                                        <b>Stake</b> <a class="float-right"></a>{{ initialState[local_winandplace[0].id + '-' + local_winandplace[1]] }}<br>
                                                        <b>Winning amount </b> <a class="float-right">{{ winning_amount[local_winandplace[0].id + '-' + local_winandplace[1]] }}</a><br>
                                                        <hr>
                                                    </ul>
                                                    <!--getting total values-->
                                                    <b>Total odds</b> <a class="float-right" v-if="total_odd === 1">0</a><a class="float-right" v-else> {{ total }} </a><br>
                                                    <b>Stake</b> <a class="float-right">{{ this.total_stake }}</a><br>
                                                    <b>Total Payout</b> <a class="float-right">{{ this.total_payout }}</a>
                                                    <!--barcode-->
                                                    <barcode v-bind:value="generateRandomString" :options="{ width: '40px' }"></barcode>
                                                </div>

                                                <!--data for localstorage and slip-->
                                                <ul v-for="(local_winandplace, index) in local_winandplaces" :key="index" class="list-group list-group-unbordered mb-3">
                                                    <li class="list-group-item">
                                                        <b>{{ local_winandplace[0].name}}</b>
                                                        <a class="float-right" v-if="1 === local_winandplace[1]">
                                                            {{ local_winandplace[0].win_odd }}
                                                        </a>
                                                        <a class="float-right" v-else-if="2 === local_winandplace[1]">{{ local_winandplace[0].place_odd }}</a>
                                                        <a v-else class="float-right"></a>
                                                    </li>
                                                    <div class="d-flex justify-content-center">
                                                        <!--calculation with + buttons-->
                                                    <button class="btn calculate btn-secondary btn-sm" @click="calculateValue('plus', local_winandplace[0].id + '-' + local_winandplace[1])" type="button">+</button>
                                                        <!--adding stake value-->
                                                    <input type="number" class="stake" v-model="initialState[local_winandplace[0].id + '-' + local_winandplace[1]] " @input="handleStake(local_winandplace[0].id + '-' + local_winandplace[1])"  placeholder="Add stake"/>
                                                        <!--calculation with - buttons-->
                                                        <button  class="btn calculate  btn-secondary btn-sm" @click="calculateValue('minus', local_winandplace[0].id + '-' + local_winandplace[1])" type="button">-</button>
                                                    </div>
                                                        <!--stake value-->
                                                    <b>Stake</b> <a class="float-right"></a>{{ initialState[local_winandplace[0].id + '-' + local_winandplace[1]] }}<br>
                                                    <!--Winning amount value-->
                                                    <b>Winning amount </b> <a class="float-right">{{ winning_amount[local_winandplace[0].id + '-' + local_winandplace[1]] }}</a><br>
                                                    <!--deleting each slip-->
                                                    <a href="#" class="btn btn-danger btn-block" @click="deleteValues(index,local_winandplace[0].id + '-' + local_winandplace[1])"><b>Delete</b></a>
                                                    <hr>
                                                </ul>
                                                <div v-if="this.local_winandplaces.length > 0">
                                                <barcode v-bind:value="generateRandomString" :options="{ width: '100px' }"></barcode>
                                                </div>

                                                <li class="list-group-item">
                                                    <!--buttons for adding bets-->
                                                    <button  class="btn  btn-secondary btn-sm" type="button" @click="summaryBets" value="10">10</button>
                                                    <button  class="btn  btn-secondary btn-sm" type="button" @click="summaryBets" value="30">30</button>
                                                    <button  class="btn  btn-secondary btn-sm" type="button" @click="summaryBets" value="50">50</button>
                                                    <button  class="btn  btn-secondary btn-sm" type="button" @click="summaryBets" value="100">100</button>
                                                    <button  class="btn  btn-secondary btn-sm" type="button" @click="summaryBets" value="200">200</button>

                                                    <!--getting total values-->
                                                    <b>Total odds</b> <a class="float-right" v-if="total === 1">0</a><a class="float-right" v-else> {{ total}} </a><br>
                                                    <b>Stake</b> <a class="float-right">{{ this.total_stake }}</a><br>
                                                    <b>Total Payout</b> <a class="float-right">{{ this.total_payout }}</a>
                                                    <!-- print slip-->
                                                    <a href="#" class="btn btn-primary btn-block" @click="printAll"><b>Print</b></a>
                                                </li>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import VueBarcode from 'vue-barcode';
import VueHtmlToPaper from 'vue-html-to-paper';
import {BootstrapVue} from "bootstrap-vue";

export default {
    components: {VueBarcode,VueHtmlToPaper,BootstrapVue},

    data() {
        return {
            initialState: {},
            winning_amount: {},
            clickedWin: {},
            clickedPlace: {},
            local_winandplaces: [],
            winandplacerecs: [],
            total_stake : 0,
            total_payout : 1,
        }
    },

    computed: {
        //total odds calculation
        total: function () {
            let total_odds = 1;
            this.local_winandplaces.map((i, item) => {
                total_odds = i[1] === 1 ? total_odds * i[0].win_odd : total_odds * i[0].place_odd;
            });
            return total_odds.toFixed(2)
        },
    },

    created() {
        this.loadwinandplace();
    },

    mounted() {
        //getting all items for localstorage
        this.local_winandplaces = localStorage.getItem('local_winandplaces')
            ? JSON.parse(localStorage.getItem('local_winandplaces'))
            : [];

        // this.fillInitialState();
        //getting from localstorage
        this.clickedWin = localStorage.getItem('clickedWin') ? JSON.parse(localStorage.getItem('clickedWin')) : {};
        this.clickedPlace = localStorage.getItem('clickedPlace') ? JSON.parse(localStorage.getItem('clickedPlace')) : {};
        this.initialState = localStorage.getItem('initialState') ? JSON.parse(localStorage.getItem('initialState')) : {};
        this.winning_amount = localStorage.getItem('initialWining') ?  JSON.parse(localStorage.getItem('initialWining')) : {};
    },

    methods: {
        fillInitialState() {
            console.log( this.initialState)
            this.local_winandplaces.forEach(item => {
                //keeping event id and type for checking win odd or place odd
                const [ { id }, type ] = item

                //adding initial stake value
                this.initialState = {
                    ...this.initialState,
                    [id + '-' +  type]: this.initialState[id + '-' +  type] ? this.initialState[id + '-' +  type] : 0
                }

                //adding initial winning amount value
                this.winning_amount = {
                    ...this.winning_amount,
                    [id + '-' +  type]: this.winning_amount[id + '-' +  type] ? this.winning_amount[id + '-' +  type] :  1
                }
            })
        },
        loadwinandplace() {
            axios
                .get('api/winandplace')
                .then(response => {
                    this.winandplacerecs = response.data.data;
                })
        },

        //adding values to slip
        printValues(event, i, type, index) {
            const addValues = () => {
                //this value are checking which odd is clicked and changing colors for highlight
                if (i === 1) {
                    this.clickedWin = {
                        ...this.clickedWin,
                        [event.id]: true
                    }
                } else {
                    this.clickedPlace = {
                        ...this.clickedPlace,
                        [event.id]: true
                    }
                    localStorage.setItem('clickedPlace',JSON.stringify(this.clickedPlace))
                    localStorage.setItem('initialState',JSON.stringify(this.initialState))
                }

                const data = this.winandplacerecs.filter((x) => x.id === event.id);
                data.push(i);
                const datetime = new Date();
                data.push(datetime);
                console.log(data);
                this.local_winandplaces.push(data);
                localStorage.setItem('local_winandplaces', JSON.stringify(this.local_winandplaces));
                this.fillInitialState();
            }

            if (type !== 'modal') {
                const exists = this.local_winandplaces.some((x) => x[0].id +  '-' + x[1] === event.id + '-' + i);

                if (exists) {
                    this.deleteValues(index, event.id + '-' + i)
                }
                else {
                    addValues()
                }
            } else {
                const exists = this.local_winandplaces.some((x) => x[0].id +  '-' + x[1] === event.id + '-' + i);

                if (exists) {
                    this.deleteValues(index, event.id + '-' + i)
                } else {
                    addValues()
                }
            }
        },
        //deleting values from slip
        deleteValues(row, checkItem) {
            const item_id = Number(checkItem.split("-").shift());
            const item_num = Number(checkItem.split("-").pop());

            if (item_num === 1 && this.clickedWin) delete this.clickedWin[item_id];localStorage.setItem('clickedWin',JSON.stringify(this.clickedWin))
            if (item_num === 2 && this.clickedPlace) delete this.clickedPlace[item_id];localStorage.setItem('clickedPlace',JSON.stringify(this.clickedPlace))

            this.local_winandplaces = this.local_winandplaces.filter(item => checkItem !== item[0].id + '-' + item[1])
            localStorage.setItem('local_winandplaces', JSON.stringify(this.local_winandplaces));
        },

        //adding stake value
        handleStake(param) {
            const type = +String(param).split('').at(-1)

            const [{win_odd, place_odd}] = this.local_winandplaces.find(item => {
                const [{id}, type] = item
                const finder = id + '-' + type
                if (finder === param) return item
            })

            this.winning_amount = {
                ...this.winning_amount,
                [param]: type === 1 ? win_odd * Number(this.initialState[param]) : place_odd * Number(this.initialState[param])
            }
            localStorage.setItem('initialState', JSON.stringify(this.initialState));
            localStorage.setItem('initialWining', JSON.stringify(this.winning_amount));
            this.total_stake = 0;
            this.total_payout = 1;
            this.local_winandplaces.forEach(item => {
                    this.total_stake += +this.initialState[item[0].id + '-' + item[1]];
            })
            this.total_payout = this.total * this.total_stake;
        },

        //bet summary
        summaryBets(event) {
            const number = Number(event.target.value)
            // if(Object.keys(this.initialState).length) {
            console.log({...this.initialState});
            Object.keys(this.initialState).forEach(item => {
                    this.initialState = {
                        ...this.initialState,
                        [item]:this.initialState[item] ? Number(this.initialState[item]) + number : number
                    }
                    console.log(this.initialState);
                    this.handleStake(item, number)
                })
            // } else {
                console.log({...this.initialState});
                // this.initialState = {
                //     ...this.initialState,
                //     [item]:this.initialState[item] ? Number(this.initialState[item]) + number : number
                // }
            // }

        },

        //+ and - buttons calculation
        calculateValue(stack_type, key) {
            if (stack_type === 'plus') {
                console.log(this.initialState);
                this.initialState = {
                    ...this.initialState,
                    [key]: this.initialState[key] ? Number(this.initialState[key]) + 1 : 10
                }
            } else {
                this.initialState = {
                    ...this.initialState,
                    [key]:  this.initialState[key] ? Number(this.initialState[key]) - 1 : 0
                }
            }

            this.handleStake(key)

            localStorage.setItem('initialState', JSON.stringify(this.initialState))
        },
        //generate number for barcode
        generateRandomString() {
            return Math.floor(100000000 + Math.random() * 900000000);
        },

        //clearing localstorage
        clearStorage () {
            localStorage.removeItem('clickedWin');
            localStorage.removeItem('clickedPlace');
            localStorage.removeItem('initialState');
            localStorage.removeItem('initialWining');
            localStorage.removeItem('local_winandplaces');
            this.clickedWin = {};
            this.clickedPlace = {};
            this.initialState = {};
            this.initialWining = {};
            this.local_winandplaces = [];
        },
        hideModal(id) {
            this.$root.$emit('bv::hide::modal', id, '#btnShow')
        },
        toggleModal(id) {
            this.$root.$emit('bv::toggle::modal', id, '#btnToggle')
        },
        //printing all slips
        printAll() {
            let data = [];

                this.local_winandplaces.map((i, item) => {
                    data.push({
                        'datetime_placed': i[2],
                        'date': i[0].event_date,
                        'time': i[0].event_time,
                        'event_id': i[0].event_id,
                        'event_no': i[0].event_no,
                        'draw': i[0].draw,
                        'stake': this.initialState[i[0].id + '-' + i[1]] ? this.initialState[i[0].id + '-' + i[1]] : 0 ,
                        'selected_odd': i[1] === 1 ? i[0].win_odd : i[0].place_odd,
                        'market': i[1] === 1 ? 'Win' : 'Place',
                        'total_stake': this.total_stake,
                        'total_odd': parseInt(this.total),
                        'payout' : i[1] === 1 ? i[0].win_odd : i[0].place_odd * this.initialState[i[0].id + '-' + i[1]] ? this.initialState[i[0].id + '-' + i[1]] : 0 ,
                        'total_payout' : this.total_payout,
                        'bar_code' : this.generateRandomString()
                    });
                });

                const requestOptions = {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer my-token',
                        'My-Custom-Header': 'foobar'
                    },
                    body: JSON.stringify({data: data})
                };
                //sendig data to database and clear all from storage
            fetch('api/winandplace', requestOptions)
                    .then(response => console.log(response))
                     this.clearStorage();
            let barcode = document.querySelector('.vue-barcode-element');
            barcode.style.width = '300px';
            let tag = document.getElementsByTagName('text');
            tag[0].innerHTML = '';
            this.$htmlToPaper('printable-values');


        }
    }
}
</script>

<style>
.bg-yellow{
    background: #dbdb4e;
}
.vue-barcode-element {
    width: 200px;
    height: 100px;
}
.vue-barcode-element text{
   display: none;
}

</style>
