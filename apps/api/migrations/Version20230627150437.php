<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230627150437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE action_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE action_type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE comment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE employer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "group_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE intervention_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE location_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE picture_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE picture_tag_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE priority_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE role_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE type_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE action (id BIGINT NOT NULL, action_type_id INT NOT NULL, intervention_id BIGINT NOT NULL, participant_id INT NOT NULL, begin_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_47CC8C921FEE0472 ON action (action_type_id)');
        $this->addSql('CREATE INDEX IDX_47CC8C928EAE3863 ON action (intervention_id)');
        $this->addSql('CREATE INDEX IDX_47CC8C929D1C3019 ON action (participant_id)');
        $this->addSql('COMMENT ON COLUMN action.begin_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN action.end_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE action_type (id INT NOT NULL, type_id INT NOT NULL, name TEXT NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_FA3FEC27C54C8C93 ON action_type (type_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FA3FEC275E237E06C54C8C93 ON action_type (name, type_id)');
        $this->addSql('CREATE TABLE category (id INT NOT NULL, name TEXT NOT NULL, description TEXT DEFAULT NULL, picture TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_64C19C15E237E06 ON category (name)');
        $this->addSql('CREATE TABLE comment (id BIGINT NOT NULL, author_id INT NOT NULL, intervention_id BIGINT NOT NULL, message TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('CREATE INDEX IDX_9474526C8EAE3863 ON comment (intervention_id)');
        $this->addSql('COMMENT ON COLUMN comment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE employer (id INT NOT NULL, siren TEXT NOT NULL, name TEXT NOT NULL, longitude NUMERIC(9, 6) NOT NULL, latitude NUMERIC(8, 6) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DE4CF066DB8BBA08 ON employer (siren)');
        $this->addSql('CREATE TABLE "group" (id INT NOT NULL, name TEXT NOT NULL, slug TEXT NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6DC044C55E237E06 ON "group" (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_6DC044C5989D9B62 ON "group" (slug)');
        $this->addSql('CREATE TABLE group_role (group_id INT NOT NULL, role_id INT NOT NULL, PRIMARY KEY(group_id, role_id))');
        $this->addSql('CREATE INDEX IDX_7E33D11AFE54D947 ON group_role (group_id)');
        $this->addSql('CREATE INDEX IDX_7E33D11AD60322AC ON group_role (role_id)');
        $this->addSql('CREATE TABLE intervention (id BIGINT NOT NULL, status_id INT NOT NULL, priority_id INT NOT NULL, category_id INT NOT NULL, type_id INT NOT NULL, author_id INT NOT NULL, employer_id INT NOT NULL, location_id BIGINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D11814AB6BF700BD ON intervention (status_id)');
        $this->addSql('CREATE INDEX IDX_D11814AB497B19F9 ON intervention (priority_id)');
        $this->addSql('CREATE INDEX IDX_D11814AB12469DE2 ON intervention (category_id)');
        $this->addSql('CREATE INDEX IDX_D11814ABC54C8C93 ON intervention (type_id)');
        $this->addSql('CREATE INDEX IDX_D11814ABF675F31B ON intervention (author_id)');
        $this->addSql('CREATE INDEX IDX_D11814AB41CD9E7A ON intervention (employer_id)');
        $this->addSql('CREATE INDEX IDX_D11814AB64D218E ON intervention (location_id)');
        $this->addSql('COMMENT ON COLUMN intervention.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE intervention_user (intervention_id BIGINT NOT NULL, user_id INT NOT NULL, PRIMARY KEY(intervention_id, user_id))');
        $this->addSql('CREATE INDEX IDX_822CCE8B8EAE3863 ON intervention_user (intervention_id)');
        $this->addSql('CREATE INDEX IDX_822CCE8BA76ED395 ON intervention_user (user_id)');
        $this->addSql('CREATE TABLE location (id BIGINT NOT NULL, street TEXT DEFAULT NULL, rest TEXT DEFAULT NULL, postcode TEXT DEFAULT NULL, city TEXT DEFAULT NULL, longitude NUMERIC(9, 6) NOT NULL, latitude NUMERIC(8, 6) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE picture (id BIGINT NOT NULL, intervention_id BIGINT NOT NULL, file_name TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_16DB4F89D7DF1668 ON picture (file_name)');
        $this->addSql('CREATE INDEX IDX_16DB4F898EAE3863 ON picture (intervention_id)');
        $this->addSql('COMMENT ON COLUMN picture.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE picture_picture_tag (picture_id BIGINT NOT NULL, picture_tag_id INT NOT NULL, PRIMARY KEY(picture_id, picture_tag_id))');
        $this->addSql('CREATE INDEX IDX_AD257153EE45BDBF ON picture_picture_tag (picture_id)');
        $this->addSql('CREATE INDEX IDX_AD2571532B36D501 ON picture_picture_tag (picture_tag_id)');
        $this->addSql('CREATE TABLE picture_tag (id INT NOT NULL, name TEXT NOT NULL, slug TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_336D34B05E237E06 ON picture_tag (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_336D34B0989D9B62 ON picture_tag (slug)');
        $this->addSql('CREATE TABLE priority (id INT NOT NULL, name TEXT NOT NULL, slug TEXT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62A6DC275E237E06 ON priority (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62A6DC27989D9B62 ON priority (slug)');
        $this->addSql('CREATE TABLE role (id INT NOT NULL, name TEXT NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_57698A6A5E237E06 ON role (name)');
        $this->addSql('CREATE TABLE status (id INT NOT NULL, name TEXT NOT NULL, slug TEXT NOT NULL, description TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7B00651C5E237E06 ON status (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7B00651C989D9B62 ON status (slug)');
        $this->addSql('CREATE TABLE type (id INT NOT NULL, category_id INT NOT NULL, name TEXT NOT NULL, description TEXT DEFAULT NULL, picture TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8CDE572912469DE2 ON type (category_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8CDE57295E237E0612469DE2 ON type (name, category_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, employer_id INT NOT NULL, login TEXT NOT NULL, password TEXT NOT NULL, firstname TEXT NOT NULL, lastname TEXT NOT NULL, email TEXT DEFAULT NULL, phone_number TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, picture TEXT DEFAULT NULL, active BOOLEAN DEFAULT true NOT NULL, connected_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649AA08CB10 ON "user" (login)');
        $this->addSql('CREATE INDEX IDX_8D93D64941CD9E7A ON "user" (employer_id)');
        $this->addSql('COMMENT ON COLUMN "user".created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN "user".connected_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE user_group (user_id INT NOT NULL, group_id INT NOT NULL, PRIMARY KEY(user_id, group_id))');
        $this->addSql('CREATE INDEX IDX_8F02BF9DA76ED395 ON user_group (user_id)');
        $this->addSql('CREATE INDEX IDX_8F02BF9DFE54D947 ON user_group (group_id)');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C921FEE0472 FOREIGN KEY (action_type_id) REFERENCES action_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C928EAE3863 FOREIGN KEY (intervention_id) REFERENCES intervention (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE action ADD CONSTRAINT FK_47CC8C929D1C3019 FOREIGN KEY (participant_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE action_type ADD CONSTRAINT FK_FA3FEC27C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C8EAE3863 FOREIGN KEY (intervention_id) REFERENCES intervention (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_role ADD CONSTRAINT FK_7E33D11AFE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_role ADD CONSTRAINT FK_7E33D11AD60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB6BF700BD FOREIGN KEY (status_id) REFERENCES status (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB497B19F9 FOREIGN KEY (priority_id) REFERENCES priority (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814ABC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814ABF675F31B FOREIGN KEY (author_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB41CD9E7A FOREIGN KEY (employer_id) REFERENCES employer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE intervention ADD CONSTRAINT FK_D11814AB64D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE intervention_user ADD CONSTRAINT FK_822CCE8B8EAE3863 FOREIGN KEY (intervention_id) REFERENCES intervention (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE intervention_user ADD CONSTRAINT FK_822CCE8BA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F898EAE3863 FOREIGN KEY (intervention_id) REFERENCES intervention (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE picture_picture_tag ADD CONSTRAINT FK_AD257153EE45BDBF FOREIGN KEY (picture_id) REFERENCES picture (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE picture_picture_tag ADD CONSTRAINT FK_AD2571532B36D501 FOREIGN KEY (picture_tag_id) REFERENCES picture_tag (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE type ADD CONSTRAINT FK_8CDE572912469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D64941CD9E7A FOREIGN KEY (employer_id) REFERENCES employer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DFE54D947 FOREIGN KEY (group_id) REFERENCES "group" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');

        // Ajout des données :
        $this->addPriorities();
        $this->addStatus();
        $this->addCategories();
        $this->addTypes();
        $this->addPictureTags();

        $this->addGroups();
        $this->addRoles();
        $this->linkGroupsAndRoles();
    }


    /**
     * Ajoute les priorités.
     */
    private function addPriorities(): void
    {
        $priorities = [
            ['id' => 1, 'name' => 'Normal', 'slug' => 'normal'],
            ['id' => 2, 'name' => 'Urgent', 'slug' => 'urgent']
        ];

        foreach($priorities as $priority) {
            $this->addSql('
                INSERT INTO priority
                    (id, name, slug)
                VALUES
                    (:id, :name, :slug)',
                $priority
            );
        }
    }

    /**
     * Ajoute les status.
     */
    private function addStatus(): void
    {
        $statuses = [
            ['id' => 1, 'name' => 'Non assignée', 'slug' => 'not-assigned', 'description' => "Lorsqu'aucun participant n'est assigné à l'intervention."],
            ['id' => 2, 'name' => 'À faire', 'slug' => 'to-do', 'description' => "Lorsque l'intervention est à faire."],
            ['id' => 3, 'name' => 'En cours', 'slug' => 'in-progress', 'description' => "Lorsque l'intervention est en cours."],
            ['id' => 4, 'name' => 'Bloquée', 'slug' => 'blocked', 'description' => "Lorsque l'intervention bloquée."],
            ['id' => 5, 'name' => 'Terminée', 'slug' => 'finished', 'description' => "Lorsque l'intervention est terminée."]
        ];

        foreach($statuses as $status) {
            $this->addSql('
                INSERT INTO status
                    (id, name, slug, description)
                VALUES
                    (:id, :name, :slug, :description)',
                $status
            );
        }
    }

    /**
     * Ajoute les catégories.
     */
    private function addCategories(): void
    {
        $categories = [
            ['id' => 1, 'name' => 'Espaces verts', 'picture' => '/img/catégorie/espaces-verts.png'],
            ['id' => 2, 'name' => 'Voirie', 'picture' => '/img/catégorie/voirie.png'],
            ['id' => 3, 'name' => 'Déchets', 'picture' => '/img/catégorie/dechets.png'],
            ['id' => 4, 'name' => 'Equipement public extérieur', 'picture' => '/img/catégorie/equipement-public-exterieur.png'],
            ['id' => 5, 'name' => 'Bâtiment public', 'picture' => '/img/catégorie/batiment-public.png'],
            ['id' => 6, 'name' => 'Autres', 'picture' => '/img/catégorie/autre.png']
        ];

        foreach($categories as $category) {
            $this->addSql('
                INSERT INTO category
                    (id, name, picture)
                VALUES
                    (:id, :name, :picture)',
                $category
            );
        }
    }

    /**
     * Ajoute les types.
     */
    private function addTypes(): void
    {
        $types = [
            ['id' => 1, 'name' => 'Désherbage', 'picture' => '/img/catégorie/espaces-verts.png', 'categoryId' => 1],
            ['id' => 2, 'name' => 'Entretien pelouse', 'picture' => '/img/catégorie/espaces-verts/entretien-pelouse.png', 'categoryId' => 1],
            ['id' => 3, 'name' => 'Ramassage', 'picture' => '/img/catégorie/espaces-verts/ramassage.png', 'categoryId' => 1],
            ['id' => 4, 'name' => 'Taille', 'picture' => '/img/catégorie/espaces-verts/taille.png', 'categoryId' => 1],
            ['id' => 5, 'name' => 'Élaguage', 'picture' => '/img/catégorie/espaces-verts/elaguage.png', 'categoryId' => 1],
            ['id' => 6, 'name' => 'Engrais', 'picture' => '/img/catégorie/espaces-verts/engrais.png', 'categoryId' => 1],
            ['id' => 7, 'name' => 'Autres', 'picture' => '/img/catégorie/espaces-verts/autre.png', 'categoryId' => 1],

            ['id' => 8, 'name' => 'Nettoyage', 'picture' => '/img/catégorie/voirie/nettoyage.png', 'categoryId' => 2],
            ['id' => 9, 'name' => 'Réparation chaussée', 'picture' => '/img/catégorie/voirie/reparation-chaussee.png', 'categoryId' => 2],
            ['id' => 10, 'name' => 'Obstacle voirie', 'picture' => '/img/catégorie/voirie/obstacle-voirie.png', 'categoryId' => 2],
            ['id' => 11, 'name' => 'Grille évacuation', 'picture' => '/img/catégorie/voirie/grille-evacuation.png', 'categoryId' => 2],
            ['id' => 12, 'name' => 'Signalétique', 'picture' => '/img/catégorie/voirie/signaletique.png', 'categoryId' => 2],
            ['id' => 13, 'name' => 'Entretien trottoir', 'picture' => '/img/catégorie/voirie/entretien-trottoir.png', 'categoryId' => 2],
            ['id' => 14, 'name' => 'Autres', 'picture' => '/img/catégorie/voirie/autre.png', 'categoryId' => 2],

            ['id' => 15, 'name' => 'Vider poubelle', 'picture' => '/img/catégorie/dechets/vider-poubelle.png', 'categoryId' => 3],
            ['id' => 16, 'name' => 'Nettoyer espace de tri', 'picture' => '/img/catégorie/dechets/nettoyer-espace-tri.png', 'categoryId' => 3],
            ['id' => 17, 'name' => 'Entretien équipement', 'picture' => '/img/catégorie/dechets/entretien-equipement.png', 'categoryId' => 3],
            ['id' => 18, 'name' => 'Dépôt sauvage', 'picture' => '/img/catégorie/dechets/depot-sauvage.png', 'categoryId' => 3],
            ['id' => 19, 'name' => 'Autres', 'picture' => '/img/catégorie/dechets/autre.png', 'categoryId' => 3],

            ['id' => 20, 'name' => 'Nettoyage', 'picture' => '/img/catégorie/equipements-public-exterieur/nettoyage.png', 'categoryId' => 4],
            ['id' => 21, 'name' => 'Tags', 'picture' => '/img/catégorie/equipements-public-exterieur/tags.png', 'categoryId' => 4],
            ['id' => 22, 'name' => 'Affichage sauvage', 'picture' => '/img/catégorie/equipements-public-exterieur/affichage-sauvage.png', 'categoryId' => 4],
            ['id' => 23, 'name' => 'Luminaires', 'picture' => '/img/catégorie/equipements-public-exterieur/luminaire.png', 'categoryId' => 4],
            ['id' => 24, 'name' => 'Entretien abribus', 'picture' => '/img/catégorie/equipements-public-exterieur/entretien-abribus.png', 'categoryId' => 4],
            ['id' => 25, 'name' => 'Entretien bancs', 'picture' => '/img/catégorie/equipements-public-exterieur/entretien-banc.png', 'categoryId' => 4],
            ['id' => 26, 'name' => 'Entretien aire de jeux', 'picture' => '/img/catégorie/equipements-public-exterieur/entretien-aire-jeux.png', 'categoryId' => 4],
            ['id' => 27, 'name' => 'Sécurité aire de jeux', 'picture' => '/img/catégorie/equipements-public-exterieur/securite-aire-jeux.png', 'categoryId' => 4],
            ['id' => 28, 'name' => 'Autres', 'picture' => '/img/catégorie/equipements-public-exterieur/autre.png', 'categoryId' => 4],

            ['id' => 29, 'name' => 'Éclairage', 'picture' => '/img/catégorie/batiment-public/eclairage.png', 'categoryId' => 5],
            ['id' => 30, 'name' => 'Electricité', 'picture' => '/img/catégorie/batiment-public/electricite.png', 'categoryId' => 5],
            ['id' => 31, 'name' => 'Serrurerie', 'picture' => '/img/catégorie/batiment-public/serrurerie.png', 'categoryId' => 5],
            ['id' => 32, 'name' => 'Menuiserie', 'picture' => '/img/catégorie/batiment-public/menuiserie.png', 'categoryId' => 5],
            ['id' => 33, 'name' => 'Maçonnerie', 'picture' => '/img/catégorie/batiment-public/maconnerie.png', 'categoryId' => 5],
            ['id' => 34, 'name' => 'Peinture', 'picture' => '/img/catégorie/batiment-public/peinture.png', 'categoryId' => 5],
            ['id' => 35, 'name' => 'Plomberie', 'picture' => '/img/catégorie/batiment-public/plomberie.png', 'categoryId' => 5],
            ['id' => 36, 'name' => 'Sécurité incendie', 'picture' => '/img/catégorie/batiment-public/securite-incendie.png', 'categoryId' => 5],
            ['id' => 37, 'name' => 'Nettoyage', 'picture' => '/img/catégorie/batiment-public/nettoyage.png', 'categoryId' => 5],
            ['id' => 38, 'name' => 'Autres', 'picture' => '/img/catégorie/batiment-public/autre.png', 'categoryId' => 5],

            ['id' => 39, 'name' => 'Autres', 'picture' => '/img/catégorie/autre.png', 'categoryId' => 6]
        ];

        foreach($types as $type) {
            $this->addSql('
                INSERT INTO type
                    (id, name, picture, category_id)
                VALUES
                    (:id, :name, :picture, :categoryId)',
                $type
            );
        }
    }

    /**
     * Ajoute les étiquettes des photos.
     */
    private function addPictureTags(): void
    {
        $pictureTags = [
            ['id' => 1, 'name' => 'Avant', 'slug' => 'before'],
            ['id' => 2, 'name' => 'Après', 'slug' => 'after']
        ];

        foreach($pictureTags as $pictureTag) {
            $this->addSql('
                INSERT INTO picture_tag
                    (id, name, slug)
                VALUES
                    (:id, :name, :slug)',
                $pictureTag
            );
        }
    }


    /**
     * Ajoute les groupes.
     */
    private function addGroups(): void
    {
        $groups = [
            ['id' => 1, 'name' => 'Agent', 'slug' => 'agent'],
            ['id' => 2, 'name' => 'Directeur', 'slug' => 'director'],
            ['id' => 3, 'name' => 'Élu', 'slug' => 'elected']
        ];

        foreach($groups as $group) {
            $this->addSql('
                INSERT INTO "group"
                    (id, name, slug)
                VALUES
                    (:id, :name, :slug)',
                $group
            );
        }
    }

    /**
     * Ajoute les rôles.
     */
    private function addRoles(): void
    {
        $roles = [
            ['id' => 1, 'name' => 'ROLE_AGENT', 'description' => 'Le rôle d‘agent.'],
            ['id' => 2, 'name' => 'ROLE_DIRECTOR', 'description' => 'Le rôle de directeur.'],
            ['id' => 3, 'name' => 'ROLE_ELECTED', 'description' => 'Le rôle d‘élu.'],
            ['id' => 4, 'name' => 'ROLE_INTERVENTIONS_POST', 'description' => null],
            ['id' => 5, 'name' => 'ROLE_INTERVENTIONS_PATCH', 'description' => null],
            ['id' => 6, 'name' => 'ROLE_READ_ASSIGNED_TO_ME_INTERVENTIONS', 'description' => 'Pour qu‘un agent voit les interventions qui lui sont assignées.'],
            ['id' => 7, 'name' => 'ROLE_READ_EMPLOYER_INTERVENTIONS', 'description' => 'Pour voir les interventions.'],
            ['id' => 8, 'name' => 'ROLE_GET_ACTIVE_AGENTS', 'description' => 'Pour accéder à la liste des agents actifs.'],
            ['id' => 9, 'name' => 'ROLE_ADD_PARTICIPANTS_TO_INTERVENTION', 'description' => 'Pour ajouter des participants à une intervention.'],
            ['id' => 10, 'name' => 'ROLE_DELETE_INTERVENTION', 'description' => 'Pour supprimer une intervention.'],
            ['id' => 11, 'name' => 'ROLE_GET_USERS', 'description' => 'Pour accéder à la liste des utilisateurs d\'une collectivité.']
        ];

        foreach($roles as $role) {
            $this->addSql('
                INSERT INTO role
                    (id, name, description)
                VALUES
                    (:id, :name, :description)',
                $role
            );
        }
    }

    /**
     * Lie les groupes et les rôles.
     */
    private function linkGroupsAndRoles(): void
    {
        $links = [
            // Agent :
            ['groupId' => 1, 'roleId' => 1],
            ['groupId' => 1, 'roleId' => 4],
            ['groupId' => 1, 'roleId' => 5],
            ['groupId' => 1, 'roleId' => 6],

            // Directeur :
            ['groupId' => 2, 'roleId' => 2],
            ['groupId' => 2, 'roleId' => 4],
            ['groupId' => 2, 'roleId' => 5],
            ['groupId' => 2, 'roleId' => 7],
            ['groupId' => 2, 'roleId' => 8],
            ['groupId' => 2, 'roleId' => 9],
            ['groupId' => 2, 'roleId' => 10],
            ['groupId' => 2, 'roleId' => 11],

            // Élu :
            ['groupId' => 3, 'roleId' => 3],
            ['groupId' => 3, 'roleId' => 7]
        ];

        foreach($links as $link) {
            $this->addSql('
                INSERT INTO group_role
                    (group_id, role_id)
                VALUES
                    (:groupId, :roleId)',
                $link
            );
        }
    }


    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE action_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE action_type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE comment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE employer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "group_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE intervention_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE location_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE picture_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE picture_tag_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE priority_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE role_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE status_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE type_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('ALTER TABLE action DROP CONSTRAINT FK_47CC8C921FEE0472');
        $this->addSql('ALTER TABLE action DROP CONSTRAINT FK_47CC8C928EAE3863');
        $this->addSql('ALTER TABLE action DROP CONSTRAINT FK_47CC8C929D1C3019');
        $this->addSql('ALTER TABLE action_type DROP CONSTRAINT FK_FA3FEC27C54C8C93');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C8EAE3863');
        $this->addSql('ALTER TABLE group_role DROP CONSTRAINT FK_7E33D11AFE54D947');
        $this->addSql('ALTER TABLE group_role DROP CONSTRAINT FK_7E33D11AD60322AC');
        $this->addSql('ALTER TABLE intervention DROP CONSTRAINT FK_D11814AB6BF700BD');
        $this->addSql('ALTER TABLE intervention DROP CONSTRAINT FK_D11814AB497B19F9');
        $this->addSql('ALTER TABLE intervention DROP CONSTRAINT FK_D11814AB12469DE2');
        $this->addSql('ALTER TABLE intervention DROP CONSTRAINT FK_D11814ABC54C8C93');
        $this->addSql('ALTER TABLE intervention DROP CONSTRAINT FK_D11814ABF675F31B');
        $this->addSql('ALTER TABLE intervention DROP CONSTRAINT FK_D11814AB41CD9E7A');
        $this->addSql('ALTER TABLE intervention DROP CONSTRAINT FK_D11814AB64D218E');
        $this->addSql('ALTER TABLE intervention_user DROP CONSTRAINT FK_822CCE8B8EAE3863');
        $this->addSql('ALTER TABLE intervention_user DROP CONSTRAINT FK_822CCE8BA76ED395');
        $this->addSql('ALTER TABLE picture DROP CONSTRAINT FK_16DB4F898EAE3863');
        $this->addSql('ALTER TABLE picture_picture_tag DROP CONSTRAINT FK_AD257153EE45BDBF');
        $this->addSql('ALTER TABLE picture_picture_tag DROP CONSTRAINT FK_AD2571532B36D501');
        $this->addSql('ALTER TABLE type DROP CONSTRAINT FK_8CDE572912469DE2');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D64941CD9E7A');
        $this->addSql('ALTER TABLE user_group DROP CONSTRAINT FK_8F02BF9DA76ED395');
        $this->addSql('ALTER TABLE user_group DROP CONSTRAINT FK_8F02BF9DFE54D947');
        $this->addSql('DROP TABLE action');
        $this->addSql('DROP TABLE action_type');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE employer');
        $this->addSql('DROP TABLE "group"');
        $this->addSql('DROP TABLE group_role');
        $this->addSql('DROP TABLE intervention');
        $this->addSql('DROP TABLE intervention_user');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE picture_picture_tag');
        $this->addSql('DROP TABLE picture_tag');
        $this->addSql('DROP TABLE priority');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_group');
    }
}
