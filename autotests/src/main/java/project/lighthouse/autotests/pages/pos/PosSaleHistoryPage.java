package project.lighthouse.autotests.pages.pos;

import org.openqa.selenium.StaleElementReferenceException;
import org.openqa.selenium.TimeoutException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.items.JSInput;
import project.lighthouse.autotests.objects.web.receiptHistory.HistoryReceiptCollection;

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

    @Override
    public HistoryReceiptCollection getObjectCollection() {
        HistoryReceiptCollection historyReceiptCollection = null;
        try {
            historyReceiptCollection = new HistoryReceiptCollection(getDriver());
        } catch (TimeoutException e) {
            containsText("Продаж не найдено.");
        } catch (StaleElementReferenceException e) {
            historyReceiptCollection = new HistoryReceiptCollection(getDriver());
        }
        return historyReceiptCollection;
    }
}
