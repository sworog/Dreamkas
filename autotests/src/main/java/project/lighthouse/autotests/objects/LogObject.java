package project.lighthouse.autotests.objects;

public class LogObject {

    String id;
    String type;
    String status;
    String title;
    String finalMessage;
    String product;
    String dateCreated;
    String dateStarted;
    String dateFinished;
    String duration;

    public LogObject(String id, String type, String status, String title, String finalMessage, String product) {
        this.id = id;
        this.type = type;
        this.status = status;
        this.title = title;
        this.finalMessage = finalMessage;
        this.product = product;
    }

    public String getType() {
        return this.type;
    }

    public String getProduct() {
        return this.product;
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
