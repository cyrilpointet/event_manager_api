export class Invitation {
    constructor(rawInvitation) {
        this.id = rawInvitation.id;
        this.created_at = rawInvitation.created_at;
        this.isFromTeam = rawInvitation.is_from_team;
        this.userEmail = rawInvitation.user_email;
        this.team = {
            id: rawInvitation.team.id,
            name: rawInvitation.team.name,
        };
    }

    get createdAt() {
        const createdAt = new Date(this.created_at);
        return createdAt.toLocaleDateString("fr-FR");
    }
}
