import { SurveyHappening } from "./SurveyHappening";

export class Survey {
    constructor(rawSurvey) {
        if (!rawSurvey.happenings || rawSurvey.happenings.length < 1) {
            throw new Error();
        }

        this.id = rawSurvey.id;
        this.created_at = rawSurvey.created_at;
        this.updated_at = rawSurvey.updated_at;

        this.team = {
            id: rawSurvey.team.id,
            name: rawSurvey.team.name,
        };

        this.happenings = [];
        rawSurvey.happenings.forEach((happening) => {
            this.happenings.push(new SurveyHappening(happening));
        });
    }

    get name() {
        return this.happenings[0].name;
    }

    get description() {
        return this.happenings[0].description;
    }

    get place() {
        return this.happenings[0].place;
    }

    get members() {
        return this.happenings[0].members;
    }
}
