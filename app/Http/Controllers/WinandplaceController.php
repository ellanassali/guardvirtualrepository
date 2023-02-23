<?php
namespace App\Http\Controllers;
use App\Models\Betselection;
use App\Models\Betssummary;
use DateTime;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Winandplace;
use Illuminate\Http\Request;

class WinandplaceController extends Controller
{

    protected $Winandplace = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Winandplace $winandplace)
    {
        $this->winandplace = $winandplace;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deleteallplacepositions  = \DB::table('winandplaces')
        ->where('place_odd', '=', NULL)
        ->delete();

        $winandplace = $this->winandplace
        ->orderBy('event_id', 'Asc')
        ->orderBy('event_no', 'Asc')
        ->orderBy('draw', 'Asc')
        ->orderBy('draw', 'Asc')
        ->paginate(20);

        return response($winandplace);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Products\ProductRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        foreach ($request['data'] as $data) {
            $betSelection = Betselection::where('event_id',$data['event_id'])->where('selected_odd',$data['selected_odd'])->where('stake',$data['stake'])->first();
            if(!$betSelection) {
                $datetime = new DateTime($data['datetime_placed']);
                $betSelection = new Betselection();
                $betSelection->event_id = $data['event_id'];
                $betSelection->event_no = $data['event_no'];
                $betSelection->time_placed = $datetime->format('H:i:s');
                $betSelection->date_placed = $datetime->format('Y-m-d');
                $betSelection->draw = $data['draw'];
                $betSelection->stake = $data['stake'];
                $betSelection->payout = $data['payout'];
                $betSelection->barcode = $data['bar_code'];
                $betSelection->event_date = $data['date'];
                $betSelection->event_time = $data['time'];
                $betSelection->market = $data['market'];
                $betSelection->selected_odd = $data['selected_odd'];
                $betSelection->save();
            }
            $betSum = Betssummary::where('receiptid',$data['bar_code'])->where('total_odd',$data['total_odd'])->where('total_stake',$data['total_stake'])->where('total_payout',$data['total_payout'])->first();
            if(!$betSum) {
                $new_betssummary = new Betssummary();
                $new_betssummary->receiptid  = $data['bar_code'];
                $new_betssummary->total_odd = $data['total_odd'];
                $new_betssummary->total_stake = $data['total_stake'];
                $new_betssummary->total_payout = $data['total_payout'];
                $new_betssummary->save();
            }
            }

        return response()->json(['status'=> true]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = $this->product->with(['category', 'tags'])->findOrFail($id);

        return $this->sendResponse($product, 'Product Details');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        $product = $this->product->findOrFail($id);

        $product->update($request->all());

        // update pivot table
        $tag_ids = [];
        foreach ($request->get('tags') as $tag) {
            $existingtag = Tag::whereName($tag['text'])->first();
            if ($existingtag) {
                $tag_ids[] = $existingtag->id;
            } else {
                $newtag = Tag::create([
                    'name' => $tag['text']
                ]);
                $tag_ids[] = $newtag->id;
            }
        }
        $product->tags()->sync($tag_ids);

        return $this->sendResponse($product, 'Product Information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->authorize('isAdmin');

        $product = $this->product->findOrFail($id);

        $product->delete();

        return $this->sendResponse($product, 'Product has been Deleted');
    }

    public function upload(Request $request)
    {
        $fileName = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move(public_path('upload'), $fileName);

        return response()->json(['success' => true]);
    }
}
