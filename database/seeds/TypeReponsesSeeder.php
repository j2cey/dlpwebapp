<?php

use Illuminate\Database\Seeder;
use App\TypeReponse;

class TypeReponsesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
     public function run()
     {
        $this->createNew(1, "Autorisation Accordée", "Demande Accordee");
        $this->createNew(2, "Succes Consultation", "Succes Consultation");
        $this->createNew(3, "Autorisation Accordée (échue)", "Autorisation Accordée (échue)");
        $this->createNew(-1, "Erreur Innatendu", "Veuillez Réessayer plus tard");
        $this->createNew(-2, "Mauvaise Requete", "Mauvaise Requete");
        $this->createNew(-3, "Echec Demande - Autorisation En Cours", "Autorisation En Cours");
        $this->createNew(-4, "Demande Hors Periode Autorisée", "Désolé. Les demandes d autorisation ne sont pas déjà disponibles");
        $this->createNew(-5, "Plafond Hebdomadaire atteint", "Désolé. Vous avez atteint le Plafond Hebdomadaire pour ce type d autorisation");
        $this->createNew(-6, "Echec Consultation - Aucune Autorisation En Cours", "Aucune Autorisation En Cours");
        $this->createNew(-7, "Echec Demande - Type Demande Innactif", "Type Demande Innactif");
     }

     private function createNew($code, $name, $msg_reponse) {
         TypeReponse::create([
           'code' => $code, 'name' => $name,
           'msg_reponse' => $msg_reponse
         ]);
     }
}
