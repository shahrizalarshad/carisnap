<?php

namespace Database\Factories;

class MalayTestData
{
    /** @var list<string> */
    private const STUDIO_NAMES = [
        'Studio Cahaya Permata',
        'Lens Majlis KL',
        'Gambar Nikah by Aiman',
        'Kenangan Visual',
        'Studio Seri Pengantin',
        'Cahaya Lens Wedding',
        'Foto Santai Majlis',
        'Riz Visual Studio',
        'Studio Harmoni Pengantin',
        'Momen Indah Photography',
        'Studio Warna Cinta',
        'Kilat Majlis Studio',
        'Gambar Mesra Wedding',
        'Studio Embun Pagi',
        'Lensa Pengantin KL',
        'Studio Rasa Sayang',
        'Fokus Majlis Studio',
        'Cahaya Senja Wedding',
        'Studio Nikah Santai',
        'Gambar Ceria Majlis',
        'Studio Permata Hati',
        'Lensa Kenangan KL',
        'Studio Akad & Sanding',
        'Visual Pengantin MY',
        'Studio Merdu Cinta',
    ];

    /** @var list<string> */
    private const LOCATION_AREAS = [
        'Kuala Lumpur',
        'Petaling Jaya',
        'Shah Alam',
        'Subang Jaya',
        'Klang',
        'Ampang',
        'Cheras',
        'Putrajaya',
        'Cyberjaya',
        'Bangi',
        'Kajang',
        'Damansara',
        'Puchong',
        'Gombak',
    ];

    /** @var list<string> */
    private const BIOS = [
        'Pasukan jurugambar perkahwinan berasaskan Klang Valley dengan pengalaman lebih 5 tahun. Kami fokus pada gaya natural, candid, dan warna yang hangat supaya kenangan majlis anda kekal bermakna.',
        'Kami pakar tangkap momen akad nikah dan sanding dengan gaya dokumentari santai. Setiap majlis dirakam dengan penuh perhatian — dari persiapan pengantin hingga seruan terakhir.',
        'Studio boutique untuk pasangan yang suka foto elegan tapi tak berapa formal. Portfolio kami penuh dengan majlis kampung, hotel, dan dewan di seluruh Lembah Klang.',
        'Jurugambar & videografer pasukan kecil yang mesra, fleksibel, dan mudah berurusan. Kami bantu anda rancang rundown supaya tak terlepas detik penting pada hari majlis.',
        'Spesialis majlis Melayu moden di KL dan Selangor. Kami percaya setiap pasangan ada cerita unik — dan tugas kami ialah abadikan cerita tu dengan jujur dan cantik.',
        'Bermula dari passion rakam majlis keluarga sendiri, kini kami bantu ratusan pasangan preserve kenangan hari bahagia. Servis mesra, edit kemas, delivery tepat masa.',
    ];

    /** @var list<string> */
    private const PACKAGE_NAMES = [
        'Pakej Akad Nikah',
        'Pakej Akad & Sanding',
        'Pakej Fotografi Penuh',
        'Pakej Sanding Sahaja',
        'Pakej Foto + Video Asas',
        'Pakej Cinematic Highlight',
        'Pakej Majlis Kampung',
        'Pakej Premium Full Day',
    ];

    /** @var list<string> */
    private const DELIVERABLES = [
        "• 8 jam coverage majlis\n• 400+ foto diedit (softcopy)\n• Album premium 20 muka surat\n• Turnaround 4-6 minggu",
        "• 6 jam coverage akad & sanding\n• 250+ foto diedit\n• Same-day preview 20 keping\n• 1 video highlight 3-5 minit",
        "• 10 jam full day coverage\n• 500+ foto diedit\n• Album hardcover + mini album\n• 2 jurugambar + 1 assistant",
        "• 4 jam coverage akad sahaja\n• 150+ foto diedit\n• Softcopy via Google Drive\n• Posen group keluarga termasuk",
        "• 8 jam foto & video\n• 300+ foto + full video akad\n• Drone shot (jika lokasi sesuai)\n• Edit cinematic style",
    ];

    /** @var list<string> */
    private const PORTFOLIO_CAPTIONS = [
        'Majlis akad nikah di masjid — momen doa yang penuh makna.',
        'Sanding di dewan — warna pastel dan cahaya natural.',
        'Persiapan pengantin — detik sebelum keluar berarak.',
        'Majlis kampung di Selangor — suasana mesra dan riang.',
        'First look pasangan — emosi tulen tanpa pose berlebihan.',
        'Majlis hotel 5 bintang — elegan tapi tetap hangat.',
        'Detik silaturahim keluarga selepas akad nikah.',
        'Potret pengantin di taman — golden hour session.',
    ];

    /** @var list<string> */
    private const BOOKING_LOCATIONS = [
        'Dewan Seri Melati, Shah Alam',
        'Masjid Jamek Kampung Baru, KL',
        'Dewan Komuniti, Petaling Jaya',
        'Hotel Istana, Kuala Lumpur',
        'Dewan Orang Ramai, Klang',
        'Kompleks Islam Putrajaya',
        'Dewan Seri Cempaka, Bangi',
        'Rumah pengantin perempuan, Cheras',
    ];

    /** @var list<string> */
    private const BOOKING_MESSAGES = [
        'Assalamualaikum, kami plan majlis pada tarikh ni. Boleh share pakej yang available dan sample album?',
        'Hai, saya cari jurugambar untuk majlis akad & sanding. Lokasi di PJ, bajet dalam range yang saya isi. Boleh quote?',
        'Boleh tak datang survey lokasi dewan dulu? Kami nak pastikan angle foto okay untuk majlis indoor.',
        'Majlis kami agak simple, fokus candid family moments. Ada pakej yang sesuai tak?',
        'Hi, saya suka portfolio studio ni. Boleh confirm availability dan harga pakej foto+video asas?',
    ];

    /** @var list<string> */
    private const QUOTE_MESSAGES = [
        'Terima kasih atas permintaan anda. Ini sebut harga untuk pakej penuh termasuk edit dan album. Boleh runding kalau nak adjust.',
        'Berdasarkan tarikh dan lokasi majlis, kami cadangkan pakej ni. Slot masih available — confirm cepat ya.',
        'Sebut harga termasuk 2 jurugambar, softcopy semua foto, dan video highlight. Valid sehingga tarikh di bawah.',
        'Kami excited nak cover majlis anda! Ini quote rasmi — kalau ok, reply accept dan kita proceed WhatsApp.',
    ];

    /** @var list<string> */
    private const REVIEW_COMMENTS = [
        'Servis memang top! Foto cantik, team punctual, dan sangat senang berurusan. Highly recommend untuk majlis korang.',
        'Alhamdulillah hasil foto melepasi jangkaan. Momen keluarga semua terabadikan dengan natural. Terima kasih banyak!',
        'Team sangat mesra dan profesional. Edit pun cepat sampai. Memang puas hati dari akad sampai sanding.',
        'Harga berpatutan untuk kualiti macam ni. Communication clear, takde hidden cost. Pengantin pun selesa sepanjang majlis.',
        'Gambar candid memang the best — rasa macam tengok balik hari majlis sendiri. Terima kasih sebab buat kenangan ni istimewa.',
    ];

    /** @var list<string> */
    private const PERSON_NAMES = [
        'Siti Nurhaliza',
        'Ahmad Faiz',
        'Nurul Aina',
        'Hafiz Rahman',
        'Farah Diyana',
        'Amirul Hakimi',
        'Zulaikha Azman',
        'Irfan Hakim',
        'Nabila Safiah',
        'Danish Imran',
        'Aisyah Sofea',
        'Luqman Hakeem',
    ];

    public static function studioName(): string
    {
        return fake()->randomElement(self::STUDIO_NAMES);
    }

    public static function bio(): string
    {
        return fake()->randomElement(self::BIOS);
    }

    public static function locationArea(): string
    {
        return fake()->randomElement(self::LOCATION_AREAS);
    }

    /** @return list<string> */
    public static function coverageAreas(): array
    {
        $areas = fake()->randomElements(self::LOCATION_AREAS, fake()->numberBetween(1, 3));

        return array_values(array_unique($areas));
    }

    public static function instagramHandle(): string
    {
        return '@'.fake()->randomElement([
            'studio.cahaya',
            'lensmajlis',
            'gambar.nikah',
            'kenanganvisual',
            'seripengantin',
            'fotosantai',
            'cahayalens',
            'momenindah',
            'nikahsantai',
            'visualpengantin',
        ]);
    }

    public static function whatsappNumber(): string
    {
        return '01'.fake()->numberBetween(2, 9).fake()->numerify('#######');
    }

    public static function packageName(): string
    {
        return fake()->randomElement(self::PACKAGE_NAMES);
    }

    public static function deliverables(): string
    {
        return fake()->randomElement(self::DELIVERABLES);
    }

    public static function portfolioCaption(): string
    {
        return fake()->randomElement(self::PORTFOLIO_CAPTIONS);
    }

    public static function bookingLocation(): string
    {
        return fake()->randomElement(self::BOOKING_LOCATIONS);
    }

    public static function bookingMessage(): string
    {
        return fake()->randomElement(self::BOOKING_MESSAGES);
    }

    public static function quoteMessage(): string
    {
        return fake()->randomElement(self::QUOTE_MESSAGES);
    }

    public static function reviewComment(): string
    {
        return fake()->randomElement(self::REVIEW_COMMENTS);
    }

    public static function personName(): string
    {
        return fake()->randomElement(self::PERSON_NAMES);
    }
}
