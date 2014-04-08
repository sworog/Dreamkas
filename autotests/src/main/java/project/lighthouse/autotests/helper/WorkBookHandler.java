package project.lighthouse.autotests.helper;

import org.apache.poi.openxml4j.exceptions.InvalidFormatException;
import org.apache.poi.ss.usermodel.Sheet;
import org.apache.poi.ss.usermodel.Workbook;
import org.apache.poi.ss.usermodel.WorkbookFactory;

import java.io.File;
import java.io.IOException;

/**
 * Class to handle sheet files
 */
public class WorkBookHandler {

    private Workbook workbook;

    public WorkBookHandler(File file) throws IOException, InvalidFormatException {
        workbook = WorkbookFactory.create(file);
    }

    public Sheet getSheet() {
        return workbook.getSheetAt(0);
    }
}
