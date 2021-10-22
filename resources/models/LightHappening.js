import * as dayjs from "dayjs";
import { HAPPENING_STATUS } from "../constantes/HappeningStatus";

export class LightHappening {
    constructor(rawHappening) {
        this.id = rawHappening.id;
        this.name = rawHappening.name;
        this.created_at = rawHappening.created_at;
        this.updated_at = rawHappening.updated_at;
        this.description = rawHappening.description;
        this.place = rawHappening.place;
        this.start_at = rawHappening.start_at;
        this.end_at = rawHappening.end_at;
        this.status = rawHappening.status;
        this.team = {
            id: rawHappening.team.id,
            name: rawHappening.team.name,
        };
        this.surveyId = rawHappening.survey_id;
    }

    get createdAt() {
        const createdAt = new Date(this.created_at);
        return createdAt.toLocaleDateString("fr-FR");
    }

    get updatedAt() {
        const createdAt = new Date(this.updated_at);
        return createdAt.toLocaleDateString("fr-FR");
    }

    get start() {
        return dayjs(this.start_at).utc().format("YYYY-MM-DD HH:mm");
    }

    get end() {
        return dayjs(this.end_at).utc().format("YYYY-MM-DD HH:mm");
    }

    get startAt() {
        return dayjs(this.start_at).utc().format("DD-MM-YYYY à HH:mm");
    }

    get endAt() {
        return dayjs(this.end_at).utc().format("DD-MM-YYYY à HH:mm");
    }

    get isUpcoming() {
        return new Date(this.start_at) > new Date();
    }

    get label() {
        return this.surveyId
            ? HAPPENING_STATUS.survey.name
            : HAPPENING_STATUS[this.status].name;
    }

    get color() {
        return this.surveyId
            ? HAPPENING_STATUS.survey.color
            : HAPPENING_STATUS[this.status].color;
    }
}
