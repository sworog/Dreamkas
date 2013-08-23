package project.lighthouse.autotests.objects;

public class LogObject {

    String id;
    String status;
    String title;
    String finalMessage;
    String dateCreated;
    String dateStarted;
    String dateFinished;
    String duration;

    public LogObject(String id, String status, String title, String finalMessage) {
        this.id = id;
        this.status = status;
        this.title = title;
        this.finalMessage = finalMessage;
    }

    public String getId() {
        return this.id;
    }

    public String getStatus() {
        return this.status;
    }

    public String getTitle() {
        return this.title;
    }

    public String getFinalMessage() {
        return this.finalMessage;
    }
}
