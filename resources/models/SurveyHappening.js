import * as dayjs from "dayjs";
import { HappeningMember } from "./HappeningMember";

export class SurveyHappening {
    constructor(rawHappening) {
        this.id = rawHappening.id;
        this.name = rawHappening.name;
        this.description = rawHappening.description;
        this.place = rawHappening.place;

        this.created_at = rawHappening.created_at;
        this.updated_at = rawHappening.updated_at;
        this.start_at = rawHappening.start_at;
        this.end_at = rawHappening.end_at;

        this.status = rawHappening.status;

        this.members = [];
        rawHappening.members.forEach((user) => {
            this.members.push(new HappeningMember(user));
        });
    }

    get createdAt() {
        return dayjs(this.created_at).utc().format("DD-MM-YYYY");
    }

    get updatedAt() {
        return dayjs(this.updated_at).utc().format("DD-MM-YYYY");
    }

    get startAtDay() {
        return dayjs(this.start_at).utc().format("DD-MM-YYYY");
    }

    get startAtHour() {
        return dayjs(this.start_at).utc().format("HH:mm");
    }

    get endAt() {
        return dayjs(this.end_at).utc().format("DD-MM-YYYY \n HH:mm");
    }

    get endAtDay() {
        return dayjs(this.end_at).utc().format("DD-MM-YYYY");
    }

    get endAtHour() {
        return dayjs(this.end_at).utc().format("HH:mm");
    }
}
