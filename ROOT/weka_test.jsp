<%@ page contentType="text/html; charset=utf-8" %>
<%@ page import="java.util.Date" %>
<%@	page import="weka.classifiers.meta.FilteredClassifier"%>
<%@	page import="weka.classifiers.*"%>
<%@	page import="weka.classifiers.trees.J48"%>
<%@	page import="weka.classifiers.bayes.*"%>
<%@	page import="weka.core.Instances"%>
<%@	page import="weka.filters.unsupervised.attribute.Remove"%>
<%@	page import="java.io.BufferedReader"%>
<%@	page import="java.io.FileNotFoundException"%>
<%@	page import="java.io.FileReader" %>
<%@ page import="java.io.FileWriter" %>
<%@	page import="java.io.IOException" %>

<%

		// get value from HTML
		String att1_1 = request.getParameter("att1_1");
		String att1_2 = request.getParameter("att1_2");
		String att1_3 = request.getParameter("att1_3");
		String att1_4 = request.getParameter("att1_4");
		String att1_5 = request.getParameter("att1_5");
		String att1_6 = request.getParameter("att1_6");
		String att1_7 = request.getParameter("att1_7");
		String att1_8 = request.getParameter("att1_8");
		String att1_9 = request.getParameter("att1_9");
		String att1_10 = request.getParameter("att1_10");
		String att1_11 = request.getParameter("att1_11");
		
		String att2_1 = request.getParameter("att2_1");
		String att2_2 = request.getParameter("att2_2");
		String att2_3 = request.getParameter("att2_3");
		String att2_4 = request.getParameter("att2_4");
		String att2_5 = request.getParameter("att2_5");
		String att2_6 = request.getParameter("att2_6");
		String att2_7 = request.getParameter("att2_7");
		String att2_8 = request.getParameter("att2_8");
		String att2_9 = request.getParameter("att2_9");
		String att2_10 = request.getParameter("att2_10");
		String att2_11 = request.getParameter("att2_11");
		String att2_12 = request.getParameter("att2_12");
		
		String att3_1 = request.getParameter("att3_1");
		String att3_2 = request.getParameter("att3_2");
		String att3_3 = request.getParameter("att3_3");
		String att3_4 = request.getParameter("att3_4");
		String att3_5 = request.getParameter("att3_5");
		String att3_6 = request.getParameter("att3_6");
		String att3_7 = request.getParameter("att3_7");
		
		String att4_1 = request.getParameter("att4_1");
		String att4_2 = request.getParameter("att4_2");
		String att4_3 = request.getParameter("att4_3");
		String att4_4 = request.getParameter("att4_4");
		String att4_5 = request.getParameter("att4_5");
		String att4_6 = request.getParameter("att4_6");
		String att4_7 = request.getParameter("att4_7");
		String att4_8 = request.getParameter("att4_8");
		String att4_9 = request.getParameter("att4_9");
		
		String att5_1 = request.getParameter("att5_1");
		String att5_2 = request.getParameter("att5_2");
		String att5_3 = request.getParameter("att5_3");
		String att5_4 = request.getParameter("att5_4");
		String att5_5 = request.getParameter("att5_5");
		String att5_6 = request.getParameter("att5_6");
		String att5_7 = request.getParameter("att5_7");
		

		FileWriter FW = new FileWriter("C:/APM_Setup/htdocs/ROOT/골관절염유병여부_test.arff", true);
		String str = att1_1 + "," + att1_2 + "," + att1_3 + "," + att1_4 + "," + att1_5 + "," + att1_6 + "," 
						+ att1_7 + "," + att1_8 + "," + att1_9 + "," + att1_10 + "," + att1_11 +
						",1\n";
		FW.write(str);
		FW.close();

        Instances training_data = new Instances(new BufferedReader(
                new FileReader("C:/APM_Setup/htdocs/ROOT/골관절염유병여부_training.arff")));
        training_data.setClassIndex(training_data.numAttributes() - 1);

        Instances testing_data = new Instances(new BufferedReader(
      			new FileReader("C:/APM_Setup/htdocs/ROOT/골관절염유병여부_test.arff")));
        testing_data.setClassIndex(testing_data.numAttributes() - 1);
       
        Classifier fc = new NaiveBayes();
        fc.buildClassifier(training_data);
        
		double prob_class0 = 0;
		double prob_class1 = 0;

        for (int i = 0; i < testing_data.numInstances(); i++) {
            double pred = fc.classifyInstance(testing_data.instance(i));
            out.print("Test Data " + i + " --- ");
            out.print("given value: "
                    + testing_data.classAttribute().value(
                            (int) testing_data.instance(i).classValue()));
            out.println(". predicted value: "
                    + testing_data.classAttribute().value((int) pred));
                    
            double[] prediction = fc.distributionForInstance(testing_data.get(i));
            
            prob_class0 = prediction[0];
            prob_class1 = prediction[1];       
        }
        
        String riskLv_disease1 = "";
        if(prob_class1 < 0.01)
        	riskLv_disease1 = "1";
        else if(prob_class1 >= 0.01 && prob_class1 < 0.15)
        	riskLv_disease1 = "2";
        else if(prob_class1 >= 0.15 && prob_class1 < 0.40)
        	riskLv_disease1 = "3";
        else if(prob_class1 >= 0.40 && prob_class1 < 0.99)
        	riskLv_disease1 = "4";
        else if(prob_class1 >= 0.99)
        	riskLv_disease1 = "5";
        
        
        
        
        
        
        
        
        
        FileWriter FW2 = new FileWriter("C:/APM_Setup/htdocs/ROOT/당뇨병유병여부_test.arff", true);
		String str2 = att2_1 + "," + att2_2 + "," + att2_3 + "," + att2_4 + "," + att2_5 + "," + att2_6 + "," 
						+ att2_7 + "," + att2_8 + "," + att2_9 + "," + att2_10 + "," + att2_11 + "," + att2_12 +
						",1\n";
		FW2.write(str2);
		FW2.close();

        Instances training_data2 = new Instances(new BufferedReader(
                new FileReader("C:/APM_Setup/htdocs/ROOT/당뇨병유병여부_training.arff")));
        training_data2.setClassIndex(training_data2.numAttributes() - 1);

        Instances testing_data2 = new Instances(new BufferedReader(
      			new FileReader("C:/APM_Setup/htdocs/ROOT/당뇨병유병여부_test.arff")));
        testing_data2.setClassIndex(testing_data2.numAttributes() - 1);
       
        Classifier fc2 = new NaiveBayes();
        fc2.buildClassifier(training_data2);
        

	 prob_class0 = 0;
	 prob_class1 = 0;
	 
        for (int i = 0; i < testing_data2.numInstances(); i++) {
            double pred = fc2.classifyInstance(testing_data2.instance(i));
            out.print("Test Data " + i + " --- ");
            out.print("given value: "
                    + testing_data2.classAttribute().value(
                            (int) testing_data2.instance(i).classValue()));
            out.println(". predicted value: "
                    + testing_data2.classAttribute().value((int) pred));
                    
            double[] prediction = fc2.distributionForInstance(testing_data2.get(i));
            
            prob_class0 = prediction[0];
            prob_class1 = prediction[1];       
        }
        
        String riskLv_disease2 = "";
        if(prob_class1 < 0.01)
        	riskLv_disease2 = "1";
        else if(prob_class1 >= 0.01 && prob_class1 < 0.15)
        	riskLv_disease2 = "2";
        else if(prob_class1 >= 0.15 && prob_class1 < 0.40)
        	riskLv_disease2 = "3";
        else if(prob_class1 >= 0.40 && prob_class1 < 0.99)
        	riskLv_disease2 = "4";
        else if(prob_class1 >= 0.99)
        	riskLv_disease2 = "5";
        
        
        
        
        
        
        
        FileWriter FW3 = new FileWriter("C:/APM_Setup/htdocs/ROOT/아토피유병여부_test.arff", true);
		String str3 = att3_1 + "," + att3_2 + "," + att2_2 + "," + att3_3 + "," + att3_4 + "," + att3_5 + "," + 
						att3_6 + "," + att3_7 + ",1\n";
		FW3.write(str3);
		FW3.close();

        Instances training_data3 = new Instances(new BufferedReader(
                new FileReader("C:/APM_Setup/htdocs/ROOT/아토피유병여부_training.arff")));
        training_data3.setClassIndex(training_data3.numAttributes() - 1);

        Instances testing_data3 = new Instances(new BufferedReader(
      			new FileReader("C:/APM_Setup/htdocs/ROOT/아토피유병여부_test.arff")));
        testing_data3.setClassIndex(testing_data3.numAttributes() - 1);
       
        Classifier fc3 = new NaiveBayes();
        fc3.buildClassifier(training_data3);
        

	 prob_class0 = 0;
	 prob_class1 = 0;
	 
        for (int i = 0; i < testing_data3.numInstances(); i++) {
            double pred = fc3.classifyInstance(testing_data3.instance(i));
            out.print("Test Data " + i + " --- ");
            out.print("given value: "
                    + testing_data3.classAttribute().value(
                            (int) testing_data3.instance(i).classValue()));
            out.println(". predicted value: "
                    + testing_data3.classAttribute().value((int) pred));
                    
            double[] prediction = fc3.distributionForInstance(testing_data3.get(i));
            
            prob_class0 = prediction[0];
            prob_class1 = prediction[1];       
        }
        
        String riskLv_disease3 = "";
        if(prob_class1 < 0.01)
        	riskLv_disease3 = "1";
        else if(prob_class1 >= 0.01 && prob_class1 < 0.15)
        	riskLv_disease3 = "2";
        else if(prob_class1 >= 0.15 && prob_class1 < 0.40)
        	riskLv_disease3 = "3";
        else if(prob_class1 >= 0.40 && prob_class1 < 0.99)
        	riskLv_disease3 = "4";
        else if(prob_class1 >= 0.99)
        	riskLv_disease3 = "5";
        
        
        
        
        
        
        
        
        
        
        
        
        
        
        FileWriter FW4 = new FileWriter("C:/APM_Setup/htdocs/ROOT/우울증유병여부_test.arff", true);
		String str4 = att1_7 + "," + att4_1 + "," + att4_2 + "," + att4_3 + "," + att4_4 + "," + att4_5 + "," + att4_6 + "," 
						+ att4_7 + "," + att4_8 + "," + att4_9 + ",1\n";
		FW4.write(str4);
		FW4.close();

        Instances training_data4 = new Instances(new BufferedReader(
                new FileReader("C:/APM_Setup/htdocs/ROOT/우울증유병여부_training.arff")));
        training_data4.setClassIndex(training_data4.numAttributes() - 1);

        Instances testing_data4 = new Instances(new BufferedReader(
      			new FileReader("C:/APM_Setup/htdocs/ROOT/우울증유병여부_test.arff")));
        testing_data4.setClassIndex(testing_data4.numAttributes() - 1);
       
        Classifier fc4 = new NaiveBayes();
        fc4.buildClassifier(training_data4);
        

	 prob_class0 = 0;
	 prob_class1 = 0;
	 
        for (int i = 0; i < testing_data4.numInstances(); i++) {
            double pred = fc4.classifyInstance(testing_data4.instance(i));
            out.print("Test Data " + i + " --- ");
            out.print("given value: "
                    + testing_data4.classAttribute().value(
                            (int) testing_data4.instance(i).classValue()));
            out.println(". predicted value: "
                    + testing_data4.classAttribute().value((int) pred));
                    
            double[] prediction = fc4.distributionForInstance(testing_data4.get(i));
            
            prob_class0 = prediction[0];
            prob_class1 = prediction[1];       
        }
        
        String riskLv_disease4 = "";
        if(prob_class1 < 0.01)
        	riskLv_disease4 = "1";
        else if(prob_class1 >= 0.01 && prob_class1 < 0.15)
        	riskLv_disease4 = "2";
        else if(prob_class1 >= 0.15 && prob_class1 < 0.40)
        	riskLv_disease4 = "3";
        else if(prob_class1 >= 0.40 && prob_class1 < 0.99)
        	riskLv_disease4 = "4";
        else if(prob_class1 >= 0.99)
        	riskLv_disease4 = "5";
        
        
        
        
        
        
        
        FileWriter FW5 = new FileWriter("C:/APM_Setup/htdocs/ROOT/고혈압유병여부_test.arff", true);
		String str5 = att5_1 + "," + att5_2 + "," + att5_3 + "," + att1_3 + "," + att5_4 + "," + att5_5 + "," + 
						att5_6 + "," + att5_7 + ",1\n";
		FW5.write(str5);
		FW5.close();

        Instances training_data5 = new Instances(new BufferedReader(
                new FileReader("C:/APM_Setup/htdocs/ROOT/고혈압유병여부_training.arff")));
        training_data5.setClassIndex(training_data5.numAttributes() - 1);

        Instances testing_data5 = new Instances(new BufferedReader(
      			new FileReader("C:/APM_Setup/htdocs/ROOT/고혈압유병여부_test.arff")));
        testing_data5.setClassIndex(testing_data5.numAttributes() - 1);
       
        Classifier fc5 = new NaiveBayes();
        fc5.buildClassifier(training_data5);
        

	 prob_class0 = 0;
	 prob_class1 = 0;
	 
        for (int i = 0; i < testing_data5.numInstances(); i++) {
            double pred = fc5.classifyInstance(testing_data5.instance(i));
            out.print("Test Data " + i + " --- ");
            out.print("given value: "
                    + testing_data5.classAttribute().value(
                            (int) testing_data5.instance(i).classValue()));
            out.println(". predicted value: "
                    + testing_data5.classAttribute().value((int) pred));
                    
            double[] prediction = fc5.distributionForInstance(testing_data5.get(i));
            
            prob_class0 = prediction[0];
            prob_class1 = prediction[1];       
        }
        
        String riskLv_disease5 = "";
        if(prob_class1 < 0.01)
        	riskLv_disease5 = "1";
        else if(prob_class1 >= 0.01 && prob_class1 < 0.15)
        	riskLv_disease5 = "2";
        else if(prob_class1 >= 0.15 && prob_class1 < 0.40)
        	riskLv_disease5 = "3";
        else if(prob_class1 >= 0.40 && prob_class1 < 0.99)
        	riskLv_disease5 = "4";
        else if(prob_class1 >= 0.99)
        	riskLv_disease5 = "5";
        
       

%>

<html>
<body>
	   <form action = "join_insurance_2.php" method="post" name = "frm">
			<input type="hidden" name="target_class" value="<%=riskLv_disease1%>">
			<input type="hidden" name="target_class2" value="<%=riskLv_disease2%>">
			<input type="hidden" name="target_class3" value="<%=riskLv_disease3%>">
			<input type="hidden" name="target_class4" value="<%=riskLv_disease4%>">
			<input type="hidden" name="target_class5" value="<%=riskLv_disease5%>">
		</form>
		
<script language = "javascript">
document.frm.submit();
</script>

		</body>
		</html>
