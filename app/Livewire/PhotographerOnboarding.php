<?php

namespace App\Livewire;

use App\Actions\CreatePhotographerProfile;
use App\Actions\CreatePhotographerProfileData;
use App\Enums\UserRole;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.public')]
class PhotographerOnboarding extends Component
{
    public string $business_name = '';

    public string $bio = '';

    public string $location_area = '';

    public array $coverage_areas = [];

    public string $whatsapp_number = '';

    public string $instagram_handle = '';

    public string $phone = '';

    public function mount(): void
    {
        $user = auth()->user();

        if ($user->role !== UserRole::Photographer) {
            abort(403);
        }

        if ($user->profile) {
            $this->redirect('/photographer', navigate: true);
        }

        $this->phone = $user->phone ?? '';
    }

    public function rules(): array
    {
        return [
            'business_name' => ['required', 'string', 'max:255'],
            'bio' => ['required', 'string', 'min:50', 'max:2000'],
            'location_area' => ['required', 'string', 'max:255'],
            'coverage_areas' => ['required', 'array', 'min:1'],
            'coverage_areas.*' => ['string', 'in:Kuala Lumpur,Selangor,Putrajaya'],
            'whatsapp_number' => ['required', 'string', 'max:20'],
            'instagram_handle' => ['nullable', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
        ];
    }

    public function submit(CreatePhotographerProfile $action): void
    {
        $validated = $this->validate();

        $action->execute(auth()->user(), new CreatePhotographerProfileData(
            businessName: $validated['business_name'],
            bio: $validated['bio'],
            locationArea: $validated['location_area'],
            coverageAreas: $validated['coverage_areas'],
            whatsappNumber: $validated['whatsapp_number'],
            instagramHandle: $validated['instagram_handle'] ?: null,
            phone: $validated['phone'],
        ));

        session()->flash('message', 'Profil berjaya dicipta! Pasukan kami akan semak dan sahkan profil anda tidak lama lagi.');

        $this->redirect('/photographer', navigate: true);
    }

    public function render()
    {
        return view('livewire.photographer-onboarding')
            ->title('Setup Profil Jurugambar - CariSnap')
            ->layoutData([
                'metaDescription' => 'Cipta profil jurugambar anda di CariSnap dan mula terima permintaan tempahan perkahwinan.',
            ]);
    }
}
