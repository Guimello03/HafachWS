@if($people->isNotEmpty())
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 md:grid-cols-3">
        @foreach($people as $person)
            <div class="p-4 text-center bg-white rounded shadow-sm">
                <h4 class="mb-1 font-semibold text-gray-800">{{ $person->name }}</h4>
                <p class="text-xs text-gray-500 break-all">{{ $person->uuid }}</p>
                <img src="data:image/png;base64,{{ $person->qr_base64 }}" class="w-32 h-32 mx-auto mt-2" />
            </div>
        @endforeach
    </div>

    <div class="mt-4 text-right">
        <form method="POST" action="{{ route('qr.download.pdf') }}">
            @csrf
            <input type="hidden" name="type" value="">
<input type="hidden" name="person_uuid" value="">
            <button type="submit"
                class="inline-flex items-center px-6 py-2 text-white transition bg-green-600 rounded hover:bg-green-700">
                📄 Baixar PDF
            </button>
        </form>
    </div>
@else
    <p class="text-sm text-gray-500">Nenhuma pessoa encontrada com os critérios informados.</p>
@endif
