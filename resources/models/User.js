import { LightTeam } from "./LightTeam";
import { LightHappening } from "./LightHappening";
import { Invitation } from "./Invitation";

export class User {
    constructor(rawUser) {
        this.id = rawUser.id;
        this.name = rawUser.name;
        this.email = rawUser.email;
        this.created_at = rawUser.created_at;
        this.teams = [];
        rawUser.teams.forEach((team) => {
            this.teams.push(new LightTeam(team));
        });
        this.invitations = [];
        rawUser.invitations.forEach((invitation) => {
            if (invitation.is_from_team === true) {
                this.invitations.push(new Invitation(invitation));
            }
        });
        // happenings
        this.happenings = [];
        rawUser.happenings.forEach((happening) => {
            this.happenings.push(new LightHappening(happening));
        });
        this.happenings.sort((a, b) => {
            // sort by date asc
            return new Date(a.start_at) - new Date(b.start_at);
        });
    }

    get createdAt() {
        const createdAt = new Date(this.created_at);
        return createdAt.toLocaleDateString("fr-FR");
    }

    get upcomingHappenings() {
        return this.happenings.filter((happening) => happening.isUpcoming);
    }

    getHappeningsByTeam(teamId) {
        return this.happenings.filter(
            (happening) => happening.team.id === teamId
        );
    }

    getUpcommingHappeningsByTeam(teamId) {
        return this.upcomingHappenings.filter(
            (happening) => happening.team.id === teamId
        );
    }
}
