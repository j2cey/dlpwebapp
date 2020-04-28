<?php

use Illuminate\Database\Seeder;
use App\TypeDemande;

class TypeDemandesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {
        $this->createNew("1", "1", "Deplacement Alimentaire", 3, 3, "Votre demande de déplacement Alimentaire du DATEDEBUT au DATEFIN à été validée avec succès.", "Vous avez une autorisation de déplacement Alimentaire du DATEDEBUT au DATEFIN en cours.");
        $this->createNew("2", "2", "Deplacement Sante", 2, 2, "Votre demande de déplacement de Santé du DATEDEBUT au DATEFIN à été validée avec succès.", "Vous avez une autorisation de déplacement de Santé du DATEDEBUT au DATEFIN en cours.");
        $this->createNew("3", "3", "Deplacement Urgence", 3, 7, "Votre demande de déplacement d Urgence du DATEDEBUT au DATEFIN à été validée avec succès.", "Vous avez une autorisation de déplacement d Urgence du DATEDEBUT au DATEFIN en cours.");
        $this->createNew("4", "4", "Consultation", 0, 0, " ", " ");
        $this->createNew("5", "5", "Mauvaise Requete", 0, 0, "Mauvaise Requete", "Mauvaise Requete");
     }

     private function createNew($code, $entry_code, $name, $validite_heure, $plafond_hebdo, $msg_succes, $msg_consultation) {
         TypeDemande::create([
           'code' => $code, 'entry_code' => $entry_code, 'name' => $name,
           'validite_heure' => $validite_heure, 'plafond_hebdo' => $plafond_hebdo,
           'msg_succes' => $msg_succes,
           'msg_consultation' => $msg_consultation
         ]);
     }
}
