<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Transkrip Nilai</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }
        h1 {
            font-size: 18px;
            margin-bottom: 4px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 20px;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 3px 0;
        }
        .info-label {
            width: 150px;
            color: #666;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data th {
            background-color: #f3f4f6;
            text-align: left;
            padding: 8px;
            border-bottom: 2px solid #ccc;
            font-size: 11px;
        }
        table.data td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .summary {
            margin-top: 20px;
            padding: 15px;
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
        }
        .summary-item {
            display: inline-block;
            width: 48%;
        }
        .summary-label {
            color: #666;
            font-size: 11px;
        }
        .summary-value {
            font-size: 20px;
            font-weight: bold;
            color: #1e40af;
        }
        .footer {
            margin-top: 40px;
            font-size: 10px;
            color: #999;
        }
    </style>
</head>
<body>

    <h1>Transkrip Nilai Akademik</h1>
    <p class="subtitle">Campus LMS</p>

    <table class="info-table">
        <tr>
            <td class="info-label">Nama</td>
            <td>: {{ $student->user->name }}</td>
        </tr>
        <tr>
            <td class="info-label">NIM</td>
            <td>: {{ $student->nim }}</td>
        </tr>
        <tr>
            <td class="info-label">Program Studi</td>
            <td>: {{ $student->studyProgram->name }}</td>
        </tr>
        <tr>
            <td class="info-label">Angkatan</td>
            <td>: {{ $student->angkatan }}</td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>Mata Kuliah</th>
                <th>SKS</th>
                <th>Tahun Ajaran</th>
                <th>Nilai</th>
                <th>Huruf</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($grades as $grade)
                <tr>
                    <td>{{ $grade->classRoom->course->name }}</td>
                    <td>{{ $grade->classRoom->course->sks }}</td>
                    <td>{{ $grade->classRoom->academicYear->year }}</td>
                    <td>{{ $grade->score }}</td>
                    <td>{{ $grade->letter }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Belum ada nilai akhir yang tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="summary">
        <div class="summary-item">
            <p class="summary-label">Total SKS Ditempuh</p>
            <p class="summary-value">{{ $totalSks }}</p>
        </div>
        <div class="summary-item">
            <p class="summary-label">IPK (Indeks Prestasi Kumulatif)</p>
            <p class="summary-value">{{ $ipk }}</p>
        </div>
    </div>

    <p class="footer">
        Dokumen ini dicetak otomatis dari sistem Campus LMS pada {{ now()->format('d F Y, H:i') }} WIB.
    </p>

</body>
</html>