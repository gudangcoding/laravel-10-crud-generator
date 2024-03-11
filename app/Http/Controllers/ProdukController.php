<?php

        namespace App\Http\Controllers;

        use Illuminate\Http\Request;
        use App\Models\Produk;
        use Illuminate\Support\Facades\Validator;
        use Illuminate\Support\Facades\DB; // Tambahkan penggunaan DB
        use Illuminate\Database\Eloquent\SoftDeletes;

        class ProdukController extends Controller
        {

            public function index(Request $request)
            {
                // Ambil data filter dari request POST
                $filters = $request->all();

                // Inisialisasi query builder
                $query = DB::table('produk');

                // Tentukan kolom primary key
                $primaryKey = '';

                // Tentukan kolom yang akan ditampilkan
                $columns = [];

                foreach ($filters as $column => $value) {
                    if ($value) {
                        if ($column === 'id') {
                            $primaryKey = $value;
                        } else {
                            $columns[] = $column;
                            $query->where($column, 'like', '%' . $value . '%');
                        }
                    }
                }

                // Eksekusi query untuk mengambil data
                $data = $query->get($columns);

                // Ubah format data sesuai dengan yang diharapkan oleh DataTables
                $formattedData = [];

                foreach ($data as $row) {
                    $checkbox = '<input type="checkbox" value="' . $row->$primaryKey . '">';
                    $actions = '
                        <div class="btn-group">
                            <button type="button" class="btn btn-xs btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-cogs"></i> Aksi
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="' . route('produk.edit', $row->$primaryKey) . '">Edit</a>
                                <a class="dropdown-item" href="' . route('produk.show', $row->$primaryKey) . '">Detail</a>
                                <div class="dropdown-divider"></div>
                                <button type="button" onclick="deleteData(\' ' . route('produk.destroy', $row->$primaryKey) . ' \')" class="dropdown-item">Delete</button>
                            </div>
                        </div>
                    ';

                    $formattedData[] = array_merge((array) $row, ['checkbox' => $checkbox, 'aksi' => $actions]);
                }

                return response()->json([
                    'data' => $formattedData
                ]);
            }


            /**
             * Menampilkan form untuk membuat data produk baru.
             */
            public function create()
            {
                return view('Produk/ProdukController.create');
            }

            /**
             * Menyimpan data produk baru ke database.
             */
            public function store(Request $request)
            {
                // Validasi input
                $validator = Validator::make($request->all(), [
                    // Lakukan validasi sesuai dengan struktur kolom yang dibuat
                    'tes' => 'required','bnbn' => 'required',
            ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                // Simpan data produk ke database
                Produk::create($request->all());

                return redirect()->route('Produk/ProdukController.index')->with('success', 'produk berhasil disimpan.');
            }

            /**
             * Menampilkan detail data produk.
             */
            public function show($id)
            {
                $data = Produk::findOrFail($id);
                return view('Produk/ProdukController.show', compact('$\data'));
            }

            /**
             * Menampilkan form untuk mengedit data produk.
             */
            public function edit($id)
            {
                $data = Produk::findOrFail($id);
                return view('Produk/ProdukController.edit', compact('$data'));
            }

            /**
             * Menyimpan perubahan pada data produk ke database.
             */
            public function update(Request $request, $id)
            {
                // Validasi input
                $validator = Validator::make($request->all(), [
                    'tes' => 'required','bnbn' => 'required',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                // Update data produk
                $data = Produk::findOrFail($id);
                $data->update($request->all());

                return redirect()->route('Produk/ProdukController.index')->with('success', 'produk berhasil diperbarui.');
            }

            /**
             * Menghapus data produk dari database.
             */
            public function destroy($id)
            {
                $data = Produk::findOrFail($id);
                $data->delete();

                return redirect()->route('Produk/ProdukController.index')->with('success', 'produk berhasil dihapus.');
            }
        }
        