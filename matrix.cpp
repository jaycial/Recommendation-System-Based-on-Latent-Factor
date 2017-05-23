#include <iostream> 
#include <cstdio> 
#include <cstdlib> 
#include <cmath> 
#include <fstream>
#include <string>
#include <sstream>


using namespace std; 

 
/** 
 * double转换为string 
 */  
string convertToString(double d) {  
  ostringstream os;  
  if (os << d)  
  return os.str();  
  return "invalid conversion";  
}

void matrix_factorization(double *R,double *P,double *Q,int N,int M,int K,int steps=500000,float alpha=0.0002,float beta=0.02) 
{ 
  for(int step =0;step<steps;++step) 
  { 
    for(int i=0;i<N;++i) 
    { 
      for(int j=0;j<M;++j) 
      { 
        if(R[i*M+j]>0) 
        { 

          //这里面的error 就是公式6里面的e(i,j) 
          double error = R[i*M+j]; 

          for(int k=0;k<K;++k) 
          error -= P[i*K+k]*Q[k*M+j]; 

          //更新公式6 
          for(int k=0;k<K;++k) 
          { 
            P[i*K+k] += alpha * (2 * error * Q[k*M+j] - beta * P[i*K+k]); 
            Q[k*M+j] += alpha * (2 * error * P[i*K+k] - beta * Q[k*M+j]); 
          } 
        } 
      } 
    } 

    double loss = 0;

    //计算每一次迭代后的，loss大小，也就是原来R矩阵里面每一个非缺失值跟预测值的平方损失 
    for(int i=0;i<N;++i) 
    { 
      for(int j=0;j<M;++j) 
      { 
        if(R[i*M+j]>0) 
        { 
          double error = 0; 
          for(int k=0;k<K;++k) 
          error += P[i*K+k]*Q[k*M+j]; 
          loss += pow(R[i*M+j]-error,2); 
          for(int k=0;k<K;++k) 
            loss += (beta/2) * (pow(P[i*K+k],2) + pow(Q[k*M+j],2)); 
        } 
      } 

    } 
  
    if(loss < 0.001) 
      break;

    if (step%1000==0)
    {
      cout<<"loss:"<<loss<<endl; 
    }

  } 
} 



int main(int argc,char ** argv) 
{ 
  char data_list[512];    //用于储存矩阵数据
  char data_scale[512];   //用于储存矩阵规模
  double scale_arr[2];        
  int scale_count=0;
  const char *d = ",";    //切割标记字符

  // 读数据
  fstream out;
  out.open("martix_data/data.txt",ios::in);
  out.getline(data_scale,512,'\n');
  out.getline(data_list,512,'\n');
  out.close();

  char *p;
  p = strtok(data_scale,d);
  while(p)
  {
    scale_arr[scale_count++]=atof(p);
    p=strtok(NULL,d);
  }


  int N=scale_arr[0]; //用户数 
  int M=scale_arr[1]; //物品数 
  int K=5; //主题个数 
  double *R=new double[N*M]; 
  double *P=new double[N*K]; 
  double *Q=new double[M*K];

  // 给原始R数组赋值
  int matrix_count=0;
  p = strtok(data_list,d);
  while(p)
  {
    R[matrix_count++]=atof(p);
    p=strtok(NULL,d);
  }

  //初始化P，Q矩阵，这里简化了，通常也可以对服从正态分布的数据进行随机数生成 
  srand(1); 
  for(int i=0;i<N;++i) 
    for(int j=0;j<K;++j) 
      P[i*K+j]=rand()%9; 

  for(int i=0;i<K;++i) 
    for(int j=0;j<M;++j) 
      Q[i*M+j]=rand()%9; 
  cout <<"矩阵分解 开始" << endl; 

  matrix_factorization(R,P,Q,N,M,K); 

  cout <<"矩阵分解 结束" << endl; 

  cout<< "原始R矩阵" << endl; 
  for(int i=0;i<N;++i) 
  { 
    for(int j=0;j<M;++j) 
      cout<< R[i*M+j]<<','; 
      cout<<endl; 
  } 

  cout<< "重构出来的R矩阵" << endl; 

  for(int i=0;i<N;++i) 
  { 
    for(int j=0;j<M;++j) 
    { 
      double temp=0; 
      for (int k=0;k<K;++k) 
        temp+=P[i*K+k]*Q[k*M+j]; 
        cout<<temp<<','; 
    } 
    cout<<endl; 
  } 

  // 拼接结果字符串，写入本地data_result_list.txt
  string result_data;
  stringstream ss;
  for(int i=0;i<N;++i)  //拼接
  { 
    for(int j=0;j<M;++j) 
    { 
      double temp=0; 
      for (int k=0;k<K;++k) 
        temp+=P[i*K+k]*Q[k*M+j]; 
        result_data = result_data + convertToString(temp) + ",";
    } 
     
  }

  ofstream in;  //写入
  in.open("martix_data/data_result_list.txt",ios::trunc);
  in<<result_data;
  in.close();//关闭文件

  cout<<"写入文件完成"<<endl;

  free(P),free(Q),free(R); 
  return 0; 
}