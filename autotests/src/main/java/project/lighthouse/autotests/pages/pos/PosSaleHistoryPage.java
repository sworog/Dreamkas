package project.lighthouse.autotests.pages.pos;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.items.JSInput;

public class PosSaleHistoryPage extends CommonPosPage {

    public PosSaleHistoryPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        throw new AssertionError("Not implemented!");
    }

    @Override
    public void createElements() {
        put("дата с", new JSInput(this, "dateFrom"));
        put("дата по", new JSInput(this, "dateTo"));
    }
}
