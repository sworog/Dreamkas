package project.lighthouse.autotests.objects;

public class JobLogObject {

    String id;
    String type;
    String status;
    String title;
    String statusText;
    String product;
    String dateCreated;
    String dateStarted;
    String dateFinished;
    String duration;

    public JobLogObject(String id, String type, String status, String title, String product, String statusText) {
        this.id = id;
        this.type = type;
        this.status = status;
        this.title = title;
        this.product = product;
        this.statusText = statusText;
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

    public String getStatusText() {
        return this.statusText;
    }
}
