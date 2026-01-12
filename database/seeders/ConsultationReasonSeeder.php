<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;
use App\Models\ConsultationReason;

class ConsultationReasonSeeder extends Seeder
{
    public function run(): void
    {
        // Clear old reasons to avoid duplicates
        ConsultationReason::truncate();

        // Define reasons per specialty mapping
        // Keys must match Specialty names exactly (see SpecialtySeeder)
        $reasons = [
            'Médecine générale' => [
                'Consultation de médecine générale',
                'Certificat médical',
                'Renouvellement d\'ordonnance',
                'Vaccination',
                'Consultation pédiatrique (enfant)',
                'Urgence'
            ],
            'Dentiste' => [
                'Bilan bucco-dentaire',
                'Détartrage',
                'Urgence dentaire',
                'Devis prothèse',
                'Soins dentaires',
                'Première consultation'
            ],
            'Ophtalmologie' => [
                'Bilan de la vue',
                'Renouvellement lunettes/lentilles',
                'Fond d\'œil',
                'Consultation enfant',
                'Urgence ophtalmologique',
                'Chirurgie réfractive (Bilan)'
            ],
            'Pédiatrie' => [
                'Visite de suivi (Nourrisson)',
                'Visite de suivi (Enfant)',
                'Vaccination',
                'Maladie aiguë (Fièvre, toux...)',
                'Certificat de sport',
                'Consultation urgence'
            ],
            'Dermatologie' => [
                'Consultation de dermatologie',
                'Dépistage mélanome (Grains de beauté)',
                'Petite chirurgie',
                'Verrue',
                'Acné',
                'Urgence dermatologique'
            ],
            'Gynécologie' => [
                'Suivi gynécologique',
                'Contraception',
                'Suivi de grossesse',
                'Pose/Retrait de stérilet',
                'Echographie',
                'Bilan fertilité'
            ],
             'Cardiologie' => [
                'Consultation de cardiologie',
                'Echographie cardiaque',
                'Test d\'effort',
                'Holter',
                'Bilan hypertension',
                'Suivi post-opératoire'
            ]
        ];

        // Fetch all specialties
        $specialties = Specialty::all();

        foreach ($specialties as $specialty) {
            // Check if we have defined reasons for this specialty name
            // If strict match fails, use a default set or try partial matching?
            // For now, simpler: if not found, give generic reasons.
            
            $specialtyReasons = $reasons[$specialty->name] ?? [
                'Première consultation',
                'Consultation de suivi',
                'Urgence',
                'Bilan complet',
                'Renouvellement',
                'Avis ponctuel'
            ];

            foreach ($specialtyReasons as $reasonName) {
                ConsultationReason::create([
                    'specialty_id' => $specialty->id,
                    'name' => $reasonName
                ]);
            }
        }
    }
}
