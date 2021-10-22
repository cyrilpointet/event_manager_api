import { TeamMember } from "./TeamMember";
import { Invitation } from "./Invitation";

export class Team {
    constructor(rawTeam) {
        this.id = rawTeam.id;
        this.name = rawTeam.name;
        this.created_at = rawTeam.created_at;

        this.members = [];
        rawTeam.members.forEach((member) => {
            this.members.push(new TeamMember(member));
        });

        this.invitations = [];
        rawTeam.invitations.forEach((invitation) => {
            if (invitation.is_from_team === false) {
                this.invitations.push(new Invitation(invitation));
            }
        });
    }

    get createdAt() {
        const createdAt = new Date(this.created_at);
        return createdAt.toLocaleDateString("fr-FR");
    }

    get adminCount() {
        let admins = 0;
        this.members.forEach((elem) => {
            admins += elem.isAdmin ? 1 : 0;
        });
        return admins;
    }
}
