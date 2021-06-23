<?= $this->extend('Templates/app') ?>

<?= $this->section('content') ?>
<section class="text-gray-400 bg-gray-900 body-font">
    <div class="container px-5 py-24 mx-auto">
        <div class="flex flex-col text-center w-full mb-20">
            <h1 class="sm:text-4xl text-3xl font-medium title-font mb-2 text-white">Mapel</h1>
            <p class="lg:w-2/3 mx-auto leading-relaxed text-base">Daftar Mata Pelajaran</p>
        </div>
        <div class="lg:w-2/3 w-full mx-auto overflow-auto">
            <!-- <a href="<?= route_to('admin.mapel.new') ?>" class="mr-3 inline-block ml-auto text-white bg-indigo-500 border-0 py-1 px-3 focus:outline-none hover:bg-indigo-600 rounded mb-3">Tambah</a> -->
            <a href="#" id="tambah_mapel" class="mr-3 inline-block ml-auto text-white bg-indigo-500 border-0 py-1 px-3 focus:outline-none hover:bg-indigo-600 rounded mb-3">Tambah</a>
            <table class="table-auto w-full text-left whitespace-no-wrap">
                <thead>
                    <tr>
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-800 rounded-tl rounded-bl">
                            #
                        </th>
                        <th class="px-4 py-3 title-font tracking-wider font-medium text-white text-sm bg-gray-800">
                            Mata Pelajaran
                        </th>
                        <th class="w-10 title-font tracking-wider font-medium text-white text-sm bg-gray-800 rounded-tr rounded-br">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody id="table_admin_mapel">
                </tbody>
            </table>
        </div>
        <div class="flex pl-4 mt-4 lg:w-2/3 w-full mx-auto">
            <a href="<?= route_to('admin.home') ?>" class="text-indigo-400 inline-flex items-center md:mb-2 lg:mb-0">Kembali
                <!-- <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" class="w-4 h-4 ml-2" viewBox="0 0 24 24">
                    <path d="M5 12h14M12 5l7 7-7 7"></path>
                </svg> -->
            </a>
        </div>
    </div>
</section>
<?= $this->endSection(); ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        function initTable() {
            $.ajax({
                url: '<?= route_to('admin.mapel.data') ?>',
                type: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                },
                async: false,
                success: function(params) {
                    let html = ''
                    params.data.map((value, index) => {
                        html += `
                        <tr>
                            <td class="border-t-2 border-gray-800 px-4 py-3">${index + 1}</td>
                            <td class="border-t-2 border-gray-800 px-4 py-3">${value.nama}</td>
                            
                            <td class="border-t-2 border-gray-800 px-4 py-3 flex">
                                <a href="<?= route_to('admin.mapel.edit', '${value.id}') ?>" class="mr-3 flex ml-auto text-white bg-indigo-500 border-0 py-1 px-3 focus:outline-none hover:bg-indigo-600 rounded">Edit</a>
                                <form class="form_admin_mapel" action="<?= route_to('admin.mapel.delete', '${value.id}') ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="flex ml-auto text-white bg-red-500 border-0 py-1 px-3 focus:outline-none hover:bg-red-600 rounded">Hapus</button>
                                </form>
                            </td>
                        </tr>`
                    })
                    $('#table_admin_mapel').html(html)
                }
            })
        }
        
        initTable();

        $('#tambah_mapel').click(async function (event){
            event.preventDefault();
            await Swal.fire({
                title: 'Form tambah mapel',
                input: 'text',
                inputLabel: 'Masukkan nama mapel',
                // inputValue: inputValue,
                showCancelButton: true,
                inputValidator: (value) => {
                    if (!value) {
                        return 'Nama mapel wajib diisi!'
                    }
                    let formData = new FormData();
                    formData.append('nama', value)
                    $.ajax({
                        url: '<?= route_to('admin.mapel.create') ?>',
                        type: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(params) {
                            console.log(params)
                            if (params.status) {
                                Swal.fire({
                                    title: 'Data Mapel',
                                    text: `${params.message}`,
                                    icon: 'success',
                                    showCancelButton: false,
                                    confirmButtonColor: '#3085d6',
                                    confirmButtonText: 'Okay'
                                })
                                initTable();
                            } 
                        },
                        error: function(error) {
                            console.error(error)
                        }
                    })
                }
            })
        })

        $('.form_admin_mapel').submit(function(event) {
            event.preventDefault()
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    let formData = new FormData(this)
                    $.ajax({
                        url: $(this).attr('action'),
                        type: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function(params) {
                            if (params.status) {
                                Swal.fire(
                                    'Deleted!',
                                    `${params.message}`,
                                    'success'
                                )
                                initTable();
                            }
                        },
                        error: function(error) {
                            console.error(error)
                        }
                    })
                }
            })
        })
    })
</script>
<?= $this->endSection();
